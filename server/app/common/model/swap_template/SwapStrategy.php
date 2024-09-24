<?php

namespace app\common\model\swap_template;

use app\common\model\BaseModel;
use think\model\concern\SoftDelete;

class SwapStrategy extends BaseModel
{
    protected $name = 'swap_strategy';

    use SoftDelete;

    protected $deleteTime = 'delete_time';

    public function groups()
    {
        return $this->belongsToMany(
            'swap_template_group',
            'swap_strategy_group_relation',
            'group_id',
            'strategy_id'
        );
    }

}
