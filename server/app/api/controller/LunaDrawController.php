<?php

namespace app\api\controller;

use app\api\logic\LunaDrawLogic;
use app\common\enum\FileEnum;
use app\common\model\luna\DigitalAvatar;
use app\common\model\luna\LunaDrawingTask;
use app\common\model\luna\SwapTask;
use app\common\model\user\User;
use app\common\service\aliyun\ImageCropService;
use app\common\service\luna\LunaDrawService;
use app\common\service\UploadService;
use app\common\types\user_draft\Draft;
use think\facade\Cache;
use think\helper\Str;

class LunaDrawController extends BaseApiController
{
    protected LunaDrawService $lunaService;

    public function initialize()
    {
        parent::initialize();
        $this->lunaService = new LunaDrawService();
    }

    // 发起AI换脸作图任务
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

    // 同步作图任务状态
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

    // 上传用户图，制作数字分身（用户图有多少张人脸就创建多少个分身）
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
            // todo Luna算法端同步图片到OSS如果有延迟，可以使用应用端配置的云存储路径（仅限阿里云，需开通图片处理功能）
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

    // 用户作图记录
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
