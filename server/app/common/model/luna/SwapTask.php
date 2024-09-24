<?php

namespace app\common\model\luna;

use app\common\model\BaseModel;
use app\common\model\swap_template\SwapStrategy;
use think\model\concern\SoftDelete;

class SwapTask extends BaseModel
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

    protected $append = [
        'create_time_ts'
    ];

    protected $json = ['face_mapping', 'user_draft', 'upstream_resp', 'result_images'];

    protected $jsonAssoc = true;


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

    public function strategy()
    {
        return $this->hasOne(SwapStrategy::class, 'id', 'strategy_id');
    }

}
