<?php

namespace app\common\model;

use think\model\concern\SoftDelete;

class LunaTemplateGroup extends BaseModel
{
    protected $name = 'luna_template_group';

    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function templates()
    {
        return $this->hasMany(LunaTemplate::class, 'group_id', 'id');
    }
}
