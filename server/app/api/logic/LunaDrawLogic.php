<?php

namespace app\api\logic;

use app\common\enum\QueueNameConst;
use app\common\enum\user\AccountLogEnum;
use app\common\model\luna\SwapTask;
use app\common\model\swap_template\SwapTemplate;
use app\common\model\swap_template\SwapTemplateCollectionRelation;
use app\common\model\user\User;
use app\common\service\luna\LunaDrawService;
use app\common\types\luna\FaceMapping;
use app\common\types\luna\FaceMappingList;
use app\common\types\user_draft\Draft;
use app\common\utils\LogUtils;
use think\facade\Db;
use think\facade\Log;

class LunaDrawLogic extends DrawLogic
{
    static function getResultImagesFromRespV3($upstreamResp)
    {
        if (empty($upstreamResp) || empty($upstreamResp['messageList'] ?? [])) {
            return null;
        }
        $resultImages = [];
        foreach ($upstreamResp['messageList'] as $k => $v) {
            if (empty($v['sourceFilePath'] ?? '')) {
                continue;
            }
            $resultImages[] = $v['sourceFilePath'];
        }
        return $resultImages;
    }

    static function pollTaskStatusV3($userId, $upTaskId)
    {
        $params = compact('userId', 'upTaskId');
        $taskExist = (new SwapTask())->where([
            'user_id' => $userId,
            'up_task_id' => $upTaskId
        ])->find();
        if (!$taskExist) {
            self::setError('找不到作图任务');
            return false;
        }

        // 终点状态不会发生改变了，无需查询上游，直接返回
        if (in_array($taskExist->status, [
            SwapTask::STATUS_SUCCESS,
            SwapTask::STATUS_FAIL,
            SwapTask::STATUS_VALIDATION_FAIL,
        ])) {
            return $taskExist->toArray();
        }

        // 更新任务状态
        $res = (new LunaDrawService())->pollTaskStatus($upTaskId);
        if (false === $res) {
            return $taskExist->toArray();
        }
        $taskExist->upstream_resp = $res;
        // 上游消息状态 -1：失败 0：等待中 1：成功 -2：未找到人脸 -3 处理超时
        if ($res['status'] == 0) {
            $taskExist->status = SwapTask::STATUS_PROCESSING;
            $taskExist->result_images = self::getResultImagesFromRespV3($res);
        } else if ($res['status'] == 1) {
            // 成功
            $taskExist->status = SwapTask::STATUS_SUCCESS;
            $taskExist->result_images = self::getResultImagesFromRespV3($res);
        } else if ($res['status'] == -2) {
            $taskExist->status = SwapTask::STATUS_VALIDATION_FAIL;
            $taskExist->error_msg = $res['errorMsg'];
            LogUtils::record(new \Exception($res['errorMsg']), "识别不到人脸", compact('params', 'taskExist', 'res'));
        } else {
            // 不覆盖终点状态，有可能是上游异常
            if (in_array($taskExist->status, [SwapTask::STATUS_PROCESSING, SwapTask::STATUS_DEFAULT])) {
                $taskExist->status = SwapTask::STATUS_FAIL;
                $taskExist->error_msg = $res['errorMsg'];
                LogUtils::record(new \Exception($res['errorMsg']), "任务状态异常", compact('params', 'taskExist', 'res'));
            }
        }

        Db::startTrans();
        try {
            if (in_array($taskExist->status, [SwapTask::STATUS_FAIL, SwapTask::STATUS_VALIDATION_FAIL])) {
                // 生成失败, 返还用户余额
                self::drawBalanceHandle($userId, $taskExist->draw_number, AccountLogEnum::DRAW_INC_DRAW_FAIL);
            }

            $taskExist
                ->allowField(['status', 'error_msg', 'upstream_resp', 'result_images'])
                ->save($taskExist->getChangedData());
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }

        return $taskExist->toArray();
    }

    static function submitTaskV3(User $user, Draft $draft)
    {
        // 获取单张模板ID
        $templateIds = [];
        if ($draft->is_collection) {
            // 合辑模板需要随机选择N个单张模板出来
            foreach ($draft->templates as $item) {
                $randomCadidateIds = SwapTemplateCollectionRelation::where('collection_id', $item->id)
                    ->orderRand()
                    ->limit($draft->random_candidate_cnt)
                    ->column('template_id');
                if (empty($randomCadidateIds)) {
                    LogUtils::log('合辑模板没有单张模板', [$item->id]);
                    continue;
                }
                array_push($templateIds, ...$randomCadidateIds);
            }
        } else {
            $templateIds = array_map(fn($template) => $template->id, $draft->templates);
        }

        // 单张模板列表
        $templateList = SwapTemplate::where('is_collection', 0)
            ->whereIn('id', $templateIds)->select();

        // 人脸映射参数
        $faceMappingList = new FaceMappingList();
        if ($draft->is_collection) {
            foreach ($templateList as $template) {
                /**
                 * @var SwapTemplate $template
                 */
                $defaultFace = $template->face_list[0] ?? null;
                if (!$defaultFace) {
                    LogUtils::log('模板没有默认人脸', [$template->id]);
                    continue;
                }

                $faceMappingList->addFaceMapping(new FaceMapping(
                    $template->up_template_id,
                    [$defaultFace['id'] => $draft->user_image->up_face_id]
                ));
            }
        } else {
            // 如果是一对一作图，直接使用用户设置的人脸映射关系
            $faceMappingList = $draft->template_face_mapping;
        }

        // 作图总数
        $drawNumber = count($faceMappingList->toArray());

        // 校验余额
        $checkResult = self::checkAbleDraw($user->id, $drawNumber);
        if ($checkResult !== true) {
            self::setError($checkResult);
            return false;
        }

        // 创建作图任务
        try {
            $res = (new LunaDrawService())->submitDrawingTaskV3($faceMappingList);
        } catch (\Exception $e) {
            self::setError("上游响应异常，请重试：" . $e->getMessage());
            return false;
        }
        if (!isset($res['messageId'])) {
            $logData = [];
            LogUtils::record(new \Exception('messageId索引不存在'), "创建任务失败", $logData);
            self::setError("创建任务失败，稍候重试: " . json_encode($logData, JSON_UNESCAPED_UNICODE));
            return false;
        }

        Db::startTrans();
        try {
            // 保存到数据库
            $newTask = new SwapTask();
            $newTask->user_id = $user->id;
            $newTask->up_task_id = $res['messageId'];
            $newTask->draw_number = $drawNumber;
            $newTask->face_mapping = $faceMappingList->toArray();
            $newTask->user_draft = $draft->toArray();
            $newTask->strategy_id = $draft->strategy_id;
            $newTask->status = SwapTask::STATUS_PROCESSING;
            $newTask->save();

            // 扣除余额
            self::drawBalanceHandle($user->id, $drawNumber, AccountLogEnum::DRAW_DEC_IMAGE);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }

        // 入队列轮询任务状态
        $job = "app\\job\\LunaDrawingPollingJob";
        \think\facade\Queue::later(5, $job, [
            'user_id' => $newTask->user_id,
            'up_task_id' => $newTask->up_task_id
        ], QueueNameConst::QueueLunaDrawPollTask);

        // 预计时间
        $showPredicateTime = self::calcShowPredicateTime($res['consumingTime'] ?? 0);
        $res['show_predicate_time'] = $showPredicateTime;

        return $res;
    }

    static function calcShowPredicateTime($totalSecond)
    {
        if (!$totalSecond) {
            return "0 " . lang('Second');
        }
        if ($totalSecond < 60 * 60) {
            $num = sprintf('%d', max(round($totalSecond / 60), 1));
            $unit = $num > 1 ? 'Minutes' : 'Minute';
        } else if ($totalSecond < 3600 * 6) {
            $num = sprintf('%d', floor($totalSecond / 60 / 60));
            $unit = $num > 1 ? 'Hours' : 'Hour';
        } else {
            $num = '6';
            $unit = 'Hours';
        }

        $showPredicateTime = $num . ' ' . lang($unit);
        return $showPredicateTime;
    }
}
