<?php

namespace app\api\validate;

use app\common\validate\BaseValidate;

class SwapTemplateValidate extends BaseValidate
{

    protected $rule = [
        'id' => 'require|integer',
        'group_id' => 'require|integer',
    ];

    public function sceneGroupList()
    {
        return $this->only(['id']);
    }

    public function sceneTemplateList()
    {
        return $this->only(['id']);
    }

}
