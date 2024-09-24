<?php

namespace app\common\model;

use think\model\concern\SoftDelete;

class LunaTemplate extends BaseModel
{
    protected $name = 'luna_template';

    use SoftDelete;
    protected $deleteTime = 'delete_time';
}
