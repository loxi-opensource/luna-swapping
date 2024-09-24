<?php

namespace app\common\model\swap_template;

use app\common\model\BaseModel;
use think\model\concern\SoftDelete;

class SwapTemplateGroup extends BaseModel
{
    protected $name = 'swap_template_group';

    use SoftDelete;

    protected $deleteTime = 'delete_time';

    protected $append = ['template_cnt'];

    public function templates()
    {
        return $this->belongsToMany(
            'swap_template',
            'swap_template_group_relation',
            'template_id',
            'group_id'
        );
    }

    public function getTemplateCntAttr($value, $data)
    {
        $templateCnt = SwapTemplateGroupRelation::where('group_id', $data['id'])->count();
        return $templateCnt;
    }
}
