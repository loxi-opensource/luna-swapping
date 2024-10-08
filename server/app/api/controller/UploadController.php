<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------

namespace app\api\controller;

use app\common\enum\BusinessErrorCodeEnum;
use app\common\enum\FileEnum;
use app\common\service\aliyun\ImageGreenCheckService;
use app\common\service\UploadService;
use Exception;
use think\response\Json;


/** 上传文件
 * Class UploadController
 * @package app\api\controller
 */
class UploadController extends BaseApiController
{

    /**
     * @notes 上传图片
     * @return Json
     * @author 段誉
     * @date 2022/9/20 18:11
     */
    public function image()
    {
        try {
            $result = UploadService::image(0, $this->userId, FileEnum::SOURCE_USER);
            $isGreenImage = (new ImageGreenCheckService())->checkImage($result['uri']);

            if (!$isGreenImage) {
                return $this->fail('Image content is not allow, please upload another one', [], BusinessErrorCodeEnum::USER_UPLOAD_IMAGE_INVALID);
            }
            return $this->success('Upload success', $result);
        } catch (Exception $e) {
            return $this->fail($e->getMessage());
        }
    }


}
