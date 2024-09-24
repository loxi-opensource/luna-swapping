<?php

namespace app\api\controller;

use app\api\logic\LunaDrawLogic;
use app\api\logic\MemberLogic;
use app\api\validate\LunaDrawValidate;
use app\common\enum\CacheTagEnum;
use app\common\enum\FileEnum;
use app\common\enum\LunaDrawConst;
use app\common\enum\ShareConst;
use app\common\logic\ImageLogic;
use app\common\model\file\File;
use app\common\model\InviteTaskCouple;
use app\common\model\luna\DigitalAvatar;
use app\common\model\luna\LunaDrawingTask;
use app\common\model\luna\SwapTask;
use app\common\model\LunaTemplateGroup;
use app\common\model\recharge\RechargeOrder;
use app\common\model\user\User;
use app\common\service\aliyun\ImageCropService;
use app\common\service\FileService;
use app\common\service\luna\LunaDrawService;
use app\common\service\UploadService;
use app\common\types\user_draft\Draft;
use app\common\utils\CacheUtils;
use app\common\utils\LogUtils;
use think\db\Query;
use think\facade\Cache;
use think\helper\Arr;
use think\helper\Str;

class LunaDrawController extends BaseApiController
{

    public array $notNeedLogin = [
        'tagList', 'materialFiles', 'tagGroupList', 'multiTagListWithPreview', 'popularMaterialFiles'
    ];

    protected LunaDrawService $lunaService;

    public function initialize()
    {
        parent::initialize();
        $this->lunaService = new LunaDrawService();
    }

    function tagList()
    {
        $params = (new LunaDrawValidate())->get()->goCheck('tagList');
        // avatar 获取首页一对多写真模板分组
        // film 获取电影定妆照模板分组
        $res['tagList'] = LunaTemplateGroup::where([
            'category' => $params['tagGroupID'] == 1 ? 'avatar' : 'film',
        ])->field([
            'up_group_id as tagId',
            'up_group_name as tagName',
            'id', 'name', 'up_group_id',
            'category'
        ])->order('sort desc')->select();
        return $this->success("success", $res);
    }

    // 获取一对一换脸模板分组，带预览图
    function multiTagListWithPreview()
    {
        $params = (new LunaDrawValidate())->get()->goCheck('multiTagListWithPreview');
        $tagList = LunaTemplateGroup::with(['templates' => function (Query $query) {
            $query->field(['id', 'group_id', 'image_url', 'up_template_id'])
                ->orderRand()
                ->limit(LunaDrawConst::MultiTagListPreviewCnt);
        }])->where([
            'category' => '1v1',
        ])->field([
            'id as tagId',
            'name as tagName',
            'id', 'name', 'up_group_id',
            'category'
        ])->order('sort desc')->select();
        return $this->success("success", [
            'tagList' => $tagList
        ]);
    }

    // 获取指定分组下的全部模板图片
    function materialFiles()
    {
        $params = (new LunaDrawValidate())->get()->goCheck('materialFiles');
        $item = LunaTemplateGroup::with(['templates' => function (Query $query) {
            $query->field(['id', 'group_id', 'image_url', 'up_template_id']);
        }])->where([
            'id' => $params['id'],
        ])->field([
            'id as tagId',
            'name as tagName',
            'id', 'name', 'up_group_id', 'category'
        ])->find();
        return $this->success("success", $item->toArray());
    }

    function popularMaterialFiles()
    {
        $params = (new LunaDrawValidate())->get()->goCheck('popularMaterialFiles');
        $cacheKey = 'luna_draw_tag_files_popular:' . $params['category'] . ':' . $params['fetch_num'];
        $res = CacheUtils::remember($cacheKey, function () use ($params) {
            $res = $this->lunaService->getTagListPopular(
                LunaDrawConst::TemplateGroupCategory1v1,
                $params['category'] == '1v1',
                $params['fetch_num'],
            );
            return $res;
        }, 300, CacheTagEnum::LUNA_DRAW);

        return $this->success("success", $res);
    }

    function submitDrawingV3()
    {
        // 校验通过，发起任务
        $result = LunaDrawLogic::submitTaskV3(
            User::find($this->userId),
            new Draft($this->request->post('draft')),
        );
        if (!$result) {
            $errMsg = LunaDrawLogic::getError();
            if (Str::contains($errMsg, '创建任务失败')) {
                return $this->fail('Fail to create a oreder, please contact customer service');
            }
            return $this->fail($errMsg);
        }

        return $this->success("success", $result);
    }


    // 充值订单提交任务
    function submitDrawing()
    {
        $params = (new LunaDrawValidate())->post()->goCheck('submitDrawing');

        $tagFileList = Arr::get($params['draft'], 'tagFile');
        if (count($tagFileList) < 1 || count($tagFileList) > 10) {
            return $this->fail('Please choose 1-10 styles');
        }

        // 校验订单支付状态
        $payedOrder = (new RechargeOrder())->where([
            'user_id' => $this->userId,
            'id' => $params['orderID'],
            'pay_status' => 1,
        ])->find();
        if (!$payedOrder) {
            return $this->fail('The payment is not successful');
        }

        // 处理重试任务
        $isRetry = $params['is_retry'] ?? false;
        $shouldRetry = false;
        $taskExist = (new LunaDrawingTask())->where([
            'order_id' => $params['orderID'],
            'is_retry' => 0,
        ])->field('id,order_id,is_retry,status,create_time')->find();

        if ($isRetry) {
            if (empty($taskExist)) {
                LogUtils::record(new \Exception('原始订单任务不存在'), "发起创作任务-校验失败", $params);
                return $this->fail("Original order doesn't exist");
            }
            if ($taskExist->status == LunaDrawingTask::STATUS_SUCCESS) {
                LogUtils::record(new \Exception('该订单任务已完成，请不要重复提交'), "发起创作任务-校验失败", $params);
                return $this->fail('Order is completed, do not submit again');
            }

            $lastRetryTask = (new LunaDrawingTask())->where([
                'order_id' => $params['orderID'],
                'is_retry' => 1,
            ])->order('id desc')->field('id,order_id,is_retry,status,create_time')->find();

            $lastTaskIsRetry = !empty($lastRetryTask);
            // 还没有重试过，原始订单就是上次的下单数据
            $lastTask = $lastTaskIsRetry ? $lastRetryTask : $taskExist;

            if ($lastTask->status != LunaDrawingTask::STATUS_VALIDATION_FAIL) {
                $errorMsg = $lastTask->status == LunaDrawingTask::STATUS_SUCCESS
                    ? "Order is completed, do not submit again"
                    : "The retry order is submitting, do not submit again";
                LogUtils::record(new \Exception($errorMsg), "发起创作任务-校验失败", $params);
                return $this->fail($errorMsg);
            }

            if ($lastTaskIsRetry) {
                $retryMinGapDuration = 300; // 限流：5分钟内只能重试一次
                if ((time() - strtotime($lastTask->create_time)) < $retryMinGapDuration) {
                    LogUtils::record(new \Exception('你重试的太频繁了'), "发起创作任务-校验失败", $params);
                    return $this->fail('Do not retry so fast');
                }
            }

            $shouldRetry = true;
        }
        if (!$isRetry) {
            if ($taskExist) {
                // 订单任务已存在，可能是LunaDrawingOnPaySuccessJob异步队列已经提交了任务
                $res = (new LunaDrawingTask())->where('id', $taskExist->id)->value('upstream_resp');
                if (is_string($res)) {
                    $res = json_decode($res, true);
                }
                $showPredicateTime = LunaDrawLogic::calcShowPredicateTime($res['consumingTime'] ?? 0);
                $res['show_predicate_time'] = $showPredicateTime;
                $res['task_exist'] = true;

                return $this->success("success", $res);
            }
        }

        // 合并双人图
        if ($params['is_share'] ?? 0) {
            $draft = $params['draft'];
            $userFile = Arr::get($draft, 'userFile');
            if (!Arr::get($userFile, 'id')) {
                $userFile = $userFile[0];
            }
            $fileID = Arr::get($userFile, 'id');
            $userFileID = Arr::get($userFile, 'localFileRes.id');
            $userFileUrl = Arr::get($userFile, 'localFileRes.uri');
            $shareTask = InviteTaskCouple::field('status,id,pid,inviting_user_id,invited_user_id,inviting_file_id')
                ->find($params['share_task_id']);
            $shareTask->invited_file_id = $userFileID;
            $invitingFileUrl = FileService::getFileUrl(File::find($shareTask->inviting_file_id)->uri ?? '');
            $invitedFileUrl = $userFileUrl;
            // TODO 耗时任务
            $mergedFile = ImageLogic::mergeRemoteImages($invitingFileUrl, $invitedFileUrl);
            if (!$mergedFile) {
                return $this->fail('Failed to merge the image');
            }
            list($ossResult, $localPath) = $mergedFile;
            $res = $this->lunaService->uploadFile($localPath);
            file_exists($localPath) && unlink($localPath);
            $res['localFileRes'] = $ossResult;
            // 修改用户作图为合并图
            Arr::set($params['draft'], 'userFile', $res);
        }

        //  校验通过，发起任务
        $result = LunaDrawLogic::submitTask($this->userId, $payedOrder->draw_number,
            $params['draft'], null, $payedOrder, ($isRetry && $shouldRetry) ? 1 : 0,
            $params['is_share'] ?? 0, $params['share_task_id'] ?? 0,
        );
        if (!$result) {
            $errMsg = LunaDrawLogic::getError();
            if (Str::contains($errMsg, '创建任务失败')) {
                return $this->fail('Fail to create a oreder, please contact customer service');
            }
            return $this->fail($errMsg);
        }

        if ($params['is_share'] ?? 0) {
            // 更新分享任务状态
            $shareTask->status = ShareConst::CoupleShareTaskStatusCompleted;
            $shareTask->save();
            InviteTaskCouple::where('id', $shareTask->pid)->update([
                'status' => ShareConst::CoupleShareTaskStatusCompleted,
                'invited_file_id' => $userFileID
            ]);
        }

        return $this->success("success", $result);
    }

    // 会员提交任务
    function memberSubmitDrawing()
    {
        $params = (new LunaDrawValidate())->post()->goCheck('memberSubmitDrawing');
        $tagFileList = Arr::get($params['draft'], 'tagFile');
        if (count($tagFileList) < 1 || count($tagFileList) > 10) {
            return $this->fail('Please choose 1-10 styles');
        }

        // 检查会员状态
        $memberInfo = MemberLogic::getMemberInfo($this->userId);
        if (!$memberInfo) {
            return $this->fail('Your subscription has expired');
        }
        // 检查会员限制
        list($available, $consumedCnt) = LunaDrawLogic::getMemberLimit($memberInfo);
        if (!$available) {
            return $this->fail('You have exceeded the production limit for this billing cycle.');
        }

        //  校验通过，发起任务
        $result = LunaDrawLogic::submitTask($this->userId, $params['draw_number'],
            $params['draft'], $memberInfo);
        if (!$result) {
            $errMsg = LunaDrawLogic::getError();
            if (Str::contains($errMsg, '创建任务失败')) {
                return $this->fail('Fail to create a oreder, please contact customer service');
            }
            return $this->fail($errMsg);
        }

        return $this->success("success", $result);
    }

    function taskStatus()
    {
        $params = (new LunaDrawValidate())->get()->goCheck('taskStatus');

        $params = $this->request->all();
        $params['user_id'] = $this->userId;
        $res = LunaDrawLogic::pollTaskStatus($params);
        if (!$res) {
            return $this->fail(LunaDrawLogic::getError());
        }

        if ($res['status'] == LunaDrawingTask::STATUS_SUCCESS) {
            // 刷新过期图片
            $shouldRefreshID = LunaDrawLogic::parseExpiredImagesFromResp($res['upstream_resp']);
            if ($shouldRefreshID) {
                $data = ['data' => [$res]];
                LunaDrawLogic::updateExpiredImages([$shouldRefreshID], $data);
                $res = $data['data'][0];
            }
        }

        $showPredicateTime = '';
        if ($res['status'] == LunaDrawingTask::STATUS_PROCESSING) {
            $showPredicateTime = LunaDrawLogic::calcShowPredicateTime($res['upstream_resp']['consumingTime'] ?? 0);
        }
        $res['show_predicate_time'] = $showPredicateTime;

        return $this->success("success", $res);
    }

    function taskStatusV3()
    {
        $res = LunaDrawLogic::pollTaskStatusV3($this->userId, $this->request->get('up_task_id'));
        if (!$res) {
            return $this->fail(LunaDrawLogic::getError());
        }

        $showPredicateTime = '';
        if ($res['status'] == LunaDrawingTask::STATUS_PROCESSING) {
            $showPredicateTime = LunaDrawLogic::calcShowPredicateTime($res['upstream_resp']['consumingTime'] ?? 0);
        }
        $res['show_predicate_time'] = $showPredicateTime;
        return $this->success("success", $res);
    }


    function uploadImage()
    {
        try {
            $ossResult = UploadService::image(0, $this->userId, FileEnum::SOURCE_USER);
            $storageType = Cache::get('STORAGE_DEFAULT', 'local');
            if ($storageType == 'local') {
                $realPath = $ossResult['url'];
            } else {
                // 云存储上传的图片需要先移动到本地
                $realPath = request()->file('file')->move(dirname($ossResult['url']), basename($ossResult['url']));
            }

            $res = $this->lunaService->uploadFile($realPath);
//            file_exists($realPath) && unlink($realPath);
            $res['localFileRes'] = $ossResult;
            // 创建数字分身
            // todo Luna算法端同步图片到OSS如果有延迟，可以把使用本应用的云存储路径（仅限阿里云，需开通图片处理功能）
            $imageUrl = LunaDrawService::LUNA_OSS_BASE_URL . '/' . $res['filePath'];
            $image = new ImageCropService($imageUrl);
            $faceList = $res['fileFaceList'];
            foreach ($faceList as $face) {
                $newRow = [
                    'user_id' => $this->userId,
                    'image_url' => $imageUrl,
                    'face_url' => $image->cropByRatio($face['boundingBoxLeft'], $face['boundingBoxTop'], $face['boundingBoxWidth'], $face['boundingBoxHeight']),
                    'file_id' => $res['localFileRes']['id'],
                    'up_file_id' => $res['id'],
                    'up_face_id' => $face['id'],
                    'up_face_data' => $face,
                ];
                DigitalAvatar::create($newRow);
            }

            return $this->success('Upload success', $res);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    function myGalleryList()
    {
        // 一笔订单可能有多个重试任务，以最新的为准
        $subQuery = \think\facade\Db::name('luna_drawing_task')
            ->field('MAX(id) AS max_id')
            ->where('user_id', $this->userId)
            ->group('order_id')
            ->buildSql();
        $data = (new LunaDrawingTask())
            ->alias('t1')
            ->join([$subQuery => 't2'], 't1.id = t2.max_id')
            ->field('t1.*')
            ->where('t1.user_id', $this->userId)
            ->order('id desc')
            ->with('order')
            ->paginate($this->request->get('per_page', 10));

        $data = $data->toArray();
        if (empty($data['data'])) {
            return $this->success("success", $data);
        }

        // 处理缩略图
        $shouldRefreshMsgList = [];
        foreach ($data['data'] as $k => $r) {
            // 缩略图还没初始化过
            if (empty($r['upstream_resp_thumb'])) {
                $shouldRefreshID = $r['upstream_resp']['id'] ?? 0;
                if ($shouldRefreshID) {
                    // 全部需要初始化
                    $shouldRefreshMsgList[] = $shouldRefreshID;
                    $data['data'][$k]['upstream_resp_thumb'] = $r['upstream_resp'];
                }
                continue;
            }
            // 缩略图已经初始化了
            $shouldRefreshID = null;
            if ($r['status'] == LunaDrawingTask::STATUS_SUCCESS) {
                $shouldRefreshID = LunaDrawLogic::parseExpiredImagesFromResp($r['upstream_resp_thumb']);
            }
            if ($shouldRefreshID) {
                // 过期的才需要刷新
                $shouldRefreshMsgList[] = $shouldRefreshID;
            }
        }
        LunaDrawLogic::updateExpiredImages($shouldRefreshMsgList, $data, 1);

        // 处理原图
        $onlyThumb = $this->request->get('only_thumb', 0);
        if ($onlyThumb) {
            foreach ($data['data'] as $k => $v) {
                $data['data'][$k]['upstream_resp'] = null;
                $data['data'][$k]['result_images'] = null;
            }
        } else {
            $shouldRefreshMsgList = [];
            array_map(function ($resp) use (&$shouldRefreshMsgList) {
                $shouldRefreshID = null;
                if ($resp['status'] == LunaDrawingTask::STATUS_SUCCESS) {
                    $shouldRefreshID = LunaDrawLogic::parseExpiredImagesFromResp($resp['upstream_resp']);
                }
                if ($shouldRefreshID) {
                    $shouldRefreshMsgList[] = $shouldRefreshID;
                }
            }, $data['data']);

            LunaDrawLogic::updateExpiredImages($shouldRefreshMsgList, $data);
        }

        return $this->success("success", $data);
    }

    function myGalleryListV3()
    {
        $data = (new SwapTask())
            ->where('user_id', $this->userId)
            ->order('id desc')
            ->paginate($this->request->get('per_page', 10));

        $data = $data->toArray();
        if (empty($data['data'])) {
            return $this->success("success", $data);
        }

        return $this->success("success", $data);
    }

}
