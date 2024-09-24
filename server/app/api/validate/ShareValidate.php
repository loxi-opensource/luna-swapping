<?php

namespace app\api\validate;

use app\common\validate\BaseValidate;

class ShareValidate extends BaseValidate
{

    protected $rule = [
        'draft' => 'require',
        'id' => 'require|integer',
    ];

    public function scenecreateCoupleTask()
    {
        return $this->only(['draft']);
    }

    public function scenecoupleTaskDetail()
    {
        return $this->only(['id']);
    }

    public function scenejoinCoupleTask()
    {
        return $this->only(['id']);
    }

}
