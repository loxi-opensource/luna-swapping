<?php

namespace app\common\model\luna;

use app\common\model\BaseModel;
use app\common\model\recharge\RechargeOrder;
use app\common\service\FileService;
use think\model\concern\SoftDelete;

class LunaDrawingTask extends BaseModel
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

    protected $append = [
        'create_time_ts'
    ];

    // 任务状态：0=默认 1=处理中 2=成功 3=失败
    const STATUS_DEFAULT = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAIL = 3;
    const STATUS_VALIDATION_FAIL = 4; // 校验失败：如用户上传头像没有人脸

    public function getCreateTimeTsAttr($value, $data)
    {
        if (empty($data['create_time'] ?? '')) {
            return;
        }
        return $data['create_time'] * 1000;
    }

    public function getUserDraftAttr($value)
    {
        return json_decode(trim($value, '"'), true);
    }

    public function getUpstreamRespAttr($value)
    {
        return json_decode(trim($value, '"'), true);
    }

    public function getUpstreamRespThumbAttr($value)
    {
        return json_decode(trim($value, '"'), true);
    }

    public function getResultImagesAttr($value)
    {
        return json_decode(trim($value, '"'), true);
    }

    public function getResultImagesThumbAttr($value)
    {
        return json_decode(trim($value, '"'), true);
    }

    // 定义与 order 表的关联方法
    public function order()
    {
        return $this->hasOne(RechargeOrder::class, 'id', 'order_id');
    }
}
