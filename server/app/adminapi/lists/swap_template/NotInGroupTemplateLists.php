<?php

namespace app\adminapi\lists\swap_template;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\lists\ListsSearchInterface;
use app\common\model\swap_template\SwapTemplate;
use app\common\model\swap_template\SwapTemplateGroup;
use app\common\model\swap_template\SwapTemplateGroupRelation;

// 不在指定分组中的模板列表
class NotInGroupTemplateLists extends BaseAdminDataLists implements ListsSearchInterface
{
    public function setSearch(): array
    {
        return [
            '%like%' => ['name'],
            'in' => ['id'],
        ];
    }

    public function lists(): array
    {
        $excludeIds = SwapTemplateGroupRelation::where('group_id', $this->params['group_id'])
            ->column('template_id');
        $group = SwapTemplateGroup::find($this->params['group_id']);
        $rows = SwapTemplate::alias('st')
            ->whereNotIn('id', $excludeIds)
            ->where('is_collection', $group->is_collection)
            ->where($this->searchWhere)
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['st.id' => 'desc'])
            ->field('st.*')
            ->select()
            ->toArray();

        return $rows;
    }

    public function count(): int
    {
        $excludeIds = SwapTemplateGroupRelation::where('group_id', $this->params['group_id'])
            ->column('template_id');
        $group = SwapTemplateGroup::find($this->params['group_id']);
        return SwapTemplate::alias('st')
            ->whereNotIn('id', $excludeIds)
            ->where('is_collection', $group->is_collection)
            ->where($this->searchWhere)->count();
    }

}
