<?php

namespace app\api\validate;

use app\common\validate\BaseValidate;

class LunaDrawValidate extends BaseValidate
{

    protected $rule = [
        'tagGroupID' => 'require|integer',
        'id' => 'require|integer',
        'orderID' => 'require|integer',
        'draw_number' => 'require|integer',
        'taskID' => 'require|integer',
        'draft' => 'require',
        'is_retry' => 'in:0,1',
        'category' => 'require|in:1v1,avatar',
        'fetch_num' => 'require|integer',
        'is_share' => 'in:0,1',
        'share_task_id' => 'requireIf:is_share,1|integer',
    ];

    public function scenetagList()
    {
        return $this->only(['tagGroupID']);
    }

    public function scenematerialFiles()
    {
        return $this->only(['id']);
    }

    public function scenesubmitDrawing()
    {
        return $this->only(['orderID', 'is_retry', 'draft', 'is_share', 'share_task_id']);
    }

    public function scenememberSubmitDrawing()
    {
        return $this->only(['draft', 'draw_number']);
    }

    public function scenetaskStatus()
    {
        return $this->only(['taskID']);
    }

    public function scenemultiTagListWithPreview()
    {
        return $this->only(['category']);
    }

    public function scenepopularMaterialFiles()
    {
        return $this->only(['category', 'fetch_num']);
    }
}
