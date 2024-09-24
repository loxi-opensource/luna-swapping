<?php

namespace app\common\model\swap_template;

use app\common\model\BaseModel;
use think\model\concern\SoftDelete;

class SwapTemplate extends BaseModel
{
    protected $name = 'swap_template';

    use SoftDelete;

    protected $deleteTime = 'delete_time';

    protected $json = ['face_list'];

    protected $jsonAssoc = true;

    protected $append = ['child_template_cnt'];

    // 单张模板属于多个合辑模板
    public function parentTemplates()
    {
        return $this->belongsToMany(
            'swap_template',
            'swap_template_collection_relation',
            'collection_id',
            'template_id'
        );
    }

    // 合辑模板拥有多个单张模板
    public function childTemplates()
    {
        return $this->belongsToMany(
            'swap_template',
            'swap_template_collection_relation',
            'template_id',
            'collection_id'
        );
    }

    public function templateGroups()
    {
        return $this->belongsToMany(
            'swap_template_group',
            'swap_template_group_relation',
            'group_id',
            'template_id'
        );
    }

    public function getChildTemplateCntAttr($value, $data)
    {
        $templateCnt = SwapTemplateCollectionRelation::where('collection_id', $data['id'])->count();
        return $templateCnt;
    }

}
