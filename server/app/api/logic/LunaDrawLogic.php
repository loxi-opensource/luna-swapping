<?php

namespace app\api\logic;

use app\common\enum\QueueNameConst;
use app\common\enum\user\AccountLogEnum;
use app\common\model\luna\LunaDrawingTask;
use app\common\model\luna\SwapTask;
use app\common\model\member\MemberPackage;
use app\common\model\swap_template\SwapTemplate;
use app\common\model\swap_template\SwapTemplateCollectionRelation;
use app\common\model\user\User;
use app\common\service\luna\LunaDrawService;
use app\common\types\luna\FaceMapping;
use app\common\types\luna\FaceMappingList;
use app\common\types\user_draft\Draft;
use app\common\utils\LogUtils;
use Carbon\Carbon;
use http\Exception\InvalidArgumentException;
use think\facade\Db;
use think\facade\Log;
use think\helper\Arr;

class LunaDrawLogic extends DrawLogic
{
    static function pollTaskStatus($params)
    {
        $taskExist = (new LunaDrawingTask())->where([
            'user_id' => $params['user_id'],
            'up_task_id' => $params['taskID']
        ])->find();
        if (!$taskExist) {
            self::setError('找不到作图任务');
            return false;
        }

        // 终点状态不会发生改变了，无需查询上游，直接返回
        if (in_array($taskExist->status, [
            LunaDrawingTask::STATUS_SUCCESS,
            LunaDrawingTask::STATUS_FAIL,
            LunaDrawingTask::STATUS_VALIDATION_FAIL,
        ])) {
            return $taskExist->toArray();
        }

        // 更新任务状态
        $res = (new LunaDrawService())->pollTaskStatus($params['taskID']);
        if (false === $res) {
            return $taskExist->toArray();
        }
        $taskExist->upstream_resp = json_encode($res);
        // 上游消息状态 -1：失败 0：等待中 1：成功 -2：未找到人脸 -3 处理超时
        if ($res['status'] == 0) {
            $taskExist->status = LunaDrawingTask::STATUS_PROCESSING;
            $resultImages = [];
            foreach (($res['messageList'] ?? []) as $k => $v) {
                if ($v['enhanceFile'] ?? '') {
                    $resultImages[] = $v['enhanceFile'];
                }
            }
            $taskExist->result_images = json_encode($resultImages);
        } else if ($res['status'] == 1) {
            // 成功
            $taskExist->status = LunaDrawingTask::STATUS_SUCCESS;
            $resultImages = [];
            foreach ($res['messageList'] as $k => $v) {
                if ($v['enhanceFile'] ?? '') {
                    $resultImages[] = $v['enhanceFile'];
                }
            }
            $taskExist->result_images = json_encode($resultImages);
        } else if ($res['status'] == -2) {
            $taskExist->status = LunaDrawingTask::STATUS_VALIDATION_FAIL;
            $taskExist->error_msg = $res['errorMsg'];
            LogUtils::record(new \Exception($res['errorMsg']), "识别不到人脸", compact('params', 'taskExist', 'res'));
        } else {
            // 不覆盖终点状态，有可能是上游异常
            if (in_array($taskExist->status, [LunaDrawingTask::STATUS_PROCESSING, LunaDrawingTask::STATUS_DEFAULT])) {
                $taskExist->status = LunaDrawingTask::STATUS_FAIL;
                $taskExist->error_msg = $res['errorMsg'];
                LogUtils::record(new \Exception($res['errorMsg']), "任务状态异常", compact('params', 'taskExist', 'res'));
            }
        }

        $taskExist
            ->allowField(['status', 'error_msg', 'upstream_resp', 'result_images'])
            ->save($taskExist->getChangedData());

        // 入队列: 任务状态更新后置操作
        $job = "app\\job\\OnDrawingTaskUpdateJob";
        \think\facade\Queue::later(1, $job, [
            'is_share' => $taskExist->is_share,
            'share_task_id' => $taskExist->share_task_id,
            'task_id' => $taskExist->id,
        ], QueueNameConst::QueueOnDrawingTaskUpdate);
        return $taskExist->toArray();
    }

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

    static function getMemberLimit($userMemberInfo)
    {
        if (empty($userMemberInfo)) {
            throw new InvalidArgumentException('userMemberInfo is empty');
        }

        $uid = $userMemberInfo['user_id'] ?? 0;
        $membershipID = $userMemberInfo['id'] ?? 0;
        $memberPackage = $userMemberInfo['package_info'] ?? []; // 套餐信息
        $periodUnit = $memberPackage['period_unit'] ?? ''; // 周期单位
        $periodLimit = $memberPackage['period_draw_limit'] ?? ''; // 周期限制
        $periodStartTime = $userMemberInfo['create_time'] ?? ''; // 周期开始时间
        $periodEndTime = ''; // 下一周期的第一秒

        if (empty($uid) || empty($memberPackage) || empty($periodStartTime)) {
            throw new InvalidArgumentException('getMemberLimit invalid args');
        }

        // 从本期会员生效时间开始计算周期
        switch ($periodUnit) {
            case MemberPackage::PeriodUnitDay:
                $periodEndTime = Carbon::now()->addDay()->startOfDay()->getTimestamp();
                break;
            case MemberPackage::PeriodUnitWeek:
                $periodEndTime = Carbon::now()->addWeek()->startOfWeek()->getTimestamp();
                break;
            case MemberPackage::PeriodUnitMonth:
                $periodEndTime = Carbon::now()->addMonth()->startOfMonth()->getTimestamp();
                break;
            case MemberPackage::PeriodUnitQuarter:
                $periodEndTime = Carbon::now()->addQuarter()->startOfQuarter()->getTimestamp();
                break;
            case MemberPackage::PeriodUnitYear:
                $periodEndTime = Carbon::now()->addYear()->startOfYear()->getTimestamp();
                break;
            default:
                throw new InvalidArgumentException('unexpected period_unit');
        }
        // 本周创作次数
        $consumedCnt = LunaDrawingTask::where(['user_id' => $uid])
            ->whereBetweenTime('create_time', $periodStartTime, $periodEndTime)
            ->where('membership_id', $membershipID)
            ->whereIn('status', [LunaDrawingTask::STATUS_PROCESSING, LunaDrawingTask::STATUS_SUCCESS])
            ->count(); // 作图任务次数
//            ->sum('draw_number'); // 图片数量

        $exceedLimit = $consumedCnt >= $periodLimit;
        // [是否未超限制，当前消耗量]
        return [!$exceedLimit, $consumedCnt];
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

    static function submitTask($userID, $drawNumber, $draft, $memberInfo = null, $rechargePayedOrder = null, $newTaskIsRetry = 0, $isShare = 0, $shareTaskId = 0)
    {
        if (empty($memberInfo) && empty($rechargePayedOrder)) {
            throw new InvalidArgumentException('会员信息和充值订单信息必须传一个');
        }
        $pass = true;
        if ($rechargePayedOrder) {
            $from = 'recharge';
            $pass = validate([
                'sn' => 'require',
                'id' => 'require',
                'is_free' => 'require',
                'recharge_package_info' => 'require',
            ])->check($rechargePayedOrder->toArray());
        } else {
            $from = 'member';
            $pass = validate([
                'id' => 'require',
            ])->check($memberInfo);
        }
        if (!$pass) {
            throw new InvalidArgumentException('submitTask方法入参有误');
        }

        $tagFileList = Arr::get($draft, 'tagFile');
        if (count($tagFileList) < 1 || count($tagFileList) > 10) {
            self::setError('创建任务失败: 参数错误');
            return false;
        }

        $tagFileIDList = Arr::pluck($tagFileList, 'up_template_id');
        if (count($tagFileIDList) > $drawNumber) {
            // 选择风格数量大于作图数量
            $tagFileIDList = Arr::random($tagFileIDList, $drawNumber);
        }

        $isFree = false;
        $isWatermark = 0;
        if ($from == 'recharge') {
            if (env('zone') == 'oversea') {
                // 免费试用随机给一种模板，创作数量不变
                if (RechargeLogic::isFreeTrialPlan($rechargePayedOrder['recharge_package_info'])) {
                    $tagFileIDList = Arr::random($tagFileIDList, 1);
                }
            }

            // 免费制作的加水印
            $isFree = $rechargePayedOrder['is_free'] == 1;
            $isWatermark = $isFree ? 1 : 0;
        }

        $tagFileID = implode(',', $tagFileIDList);
        $fileID = Arr::get($draft, 'userFile.id');
        $userFileID = Arr::get($draft, 'userFile.localFileRes.id');
        if (!$fileID) {
            $userFileList = Arr::get($draft, 'userFile');
            if (count($userFileList) > 5) {
                $userFileList = Arr::random($userFileList, 5);
            }
            $fileID = implode(',', Arr::pluck($userFileList, 'id'));
            $userFileID = implode(',', Arr::pluck($userFileList, 'localFileRes.id'));
        }

        try {
//            $res = (new LunaDrawService())->submitDrawingTask($tagFileID, $fileID, $drawNumber);
            $res = (new LunaDrawService())->submitDrawingTaskV2($drawNumber, $fileID, $tagFileID);
        } catch (\Exception $e) {
            // 上游响应异常，但可以忽略。比如访问频率受限制
            self::setError("Upstream service return exception，retry again: " . $e->getMessage());
            return false;
        }
        if (!isset($res['messageId'])) {
            $logData = compact('payedRechargeOrder', 'from');
            LogUtils::record(new \Exception('messageId索引不存在'), "创建任务失败", $logData);
            self::setError("创建任务失败，稍候重试: " . json_encode($logData, JSON_UNESCAPED_UNICODE));
            return false;
        }

        $newTask = new LunaDrawingTask();
        $newTask->user_id = $userID;
        $newTask->order_sn = $rechargePayedOrder['sn'] ?? ''; // 充值订单号
        $newTask->order_id = $rechargePayedOrder['id'] ?? 0; // 充值订单ID
        $newTask->membership_id = $memberInfo['id'] ?? 0; // 会员购买记录ID
        $newTask->up_task_id = $res['messageId'];
        $newTask->draw_number = $drawNumber;
        $newTask->tag_file_id = $tagFileID;
        $newTask->user_file_id = $userFileID; // 本系统存储引擎的图片ID
        $newTask->up_user_file_id = $fileID; // 上游的用户上传图片ID
        $newTask->is_retry = $newTaskIsRetry; // 是否是重试任务
        $newTask->is_watermark = $isWatermark;
        $newTask->is_share = $isShare;
        $newTask->share_task_id = $shareTaskId;

        if (isset($draft['drawingResult'])) {
            unset($draft['drawingResult']);
        }
        if (isset($draft['orderInfo'])) {
            unset($draft['orderInfo']);
        }
        $newTask->user_draft = json_encode($draft, JSON_UNESCAPED_UNICODE);
        $newTask->status = 1;
        $newTask->save();

        if ($newTask->id && $newTask->membership_id) {
            $newTask->order_id = intval(1000000000 + $newTask->id);
            $newTask->save();
        }

        // 入队列
        $job = "app\\job\\LunaDrawingPollingJob";
        \think\facade\Queue::later(5, $job, [
            'userID' => $userID,
            'taskID' => $res['messageId']
        ], QueueNameConst::QueueLunaDrawPollTask);

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

    private static function refreshExpiredImages($msgList, $isThumbnail = 0)
    {
        if (empty($msgList)) {
            return false;
        }

        try {
            // todo debug
            LogUtils::log('freddy-请求刷新图库参数：', compact('msgList', 'isThumbnail'));
            $start1 = microtime(true); // 记录开始时间
            $res = (new LunaDrawService())->batchGetTaskStatus($msgList, $isThumbnail);
            $end1 = microtime(true); // 记录结束时间
            $time1 = round(($end1 - $start1) * 1000, 2); // 第一部分的用时
            Log::record("freddy-请求刷新图库用时：{$time1} 毫秒");
        } catch (\Exception $e) {
            Log::record("refreshExpiredImages lunaService->batchGetTaskStatus 批量刷新图片失败: " . json_encode([
                    'err' => $e->getMessage(),
                    'params' => $msgList
                ], JSON_UNESCAPED_UNICODE));
            return false;
        }

        if (empty($res)) {
            return false;
        }

        $taskMap = array_column($res['list'], null, 'id');
        return $taskMap;
    }

    static function getResultImagesFromResp($res)
    {
        if (empty($res) || empty($res['messageList'] ?? [])) {
            return null;
        }
        $resultImages = [];
        foreach ($res['messageList'] as $k => $v) {
            if (empty($v['enhanceFile'] ?? '')) {
                continue;
            }
            $resultImages[] = $v['enhanceFile'];
        }
        return $resultImages;
    }

    static function parseExpiredImagesFromResp($res)
    {
        if (empty($res) || empty($res['messageList'] ?? [])) {
            return false;
        }
//        return $res['id']; // todo debug

        $nowTime = time();
        foreach ($res['messageList'] as $k => $v) {
            if (empty($v['enhanceFile'] ?? '')) {
                // 异常情况直接刷新
                return $res['id'];
            }

            // todo 北京时间
            if (!isset($v['imageExpireTime']) || strtotime($v['imageExpireTime']) <= ($nowTime + 300)) {
                // 只要有一张图片过期了，就返回消息ID
                return $res['id'];
            }
        }

        return false;
    }

    static function updateExpiredImages($shouldRefreshMsgList, &$data, $isThumbnail = 0)
    {
//        $data = ['foo' => 'bar'];
//        return;
        $taskMap = [];
        if (!empty($shouldRefreshMsgList)) {
            $taskMap = LunaDrawLogic::refreshExpiredImages($shouldRefreshMsgList, $isThumbnail);
        }

        if ($taskMap) {
            $upstreamRespKey = self::thumbnailSuffix('upstream_resp', $isThumbnail);
            $resultImagesKey = self::thumbnailSuffix('result_images', $isThumbnail);

            foreach ($data['data'] as $k => $v) {
                $taskID = $v[$upstreamRespKey]['id'] ?? 0;
                if ($taskID && isset($taskMap[$taskID])) {
                    $newResp = $taskMap[$taskID];
                    $data['data'][$k][$upstreamRespKey] = $newResp;
                    $newResultImages = LunaDrawLogic::getResultImagesFromResp($newResp);
                    (new LunaDrawingTask())->update([
                        $upstreamRespKey => json_encode($newResp),
                        $resultImagesKey => json_encode($newResultImages),
                    ], ['id' => $v['id']]);
                }

                $data['data'][$k][$resultImagesKey] =
                    LunaDrawLogic::getResultImagesFromResp($data['data'][$k][$upstreamRespKey] ?? '');
                $data['data'][$k]['create_at_date'] = date("m-d", strtotime($v['create_time']));
            }
        }
    }

    static function thumbnailSuffix($origin, $isThumbnail)
    {
        if (!$isThumbnail) {
            return $origin;
        }
        return $origin . '_thumb';
    }

}
