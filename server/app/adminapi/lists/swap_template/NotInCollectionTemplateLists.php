<?php

namespace app\adminapi\lists\swap_template;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\lists\ListsSearchInterface;
use app\common\model\swap_template\SwapTemplate;
use app\common\model\swap_template\SwapTemplateCollectionRelation;

// 不在指定合辑中的模板列表
class NotInCollectionTemplateLists extends BaseAdminDataLists implements ListsSearchInterface
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
        $excludeIds = SwapTemplateCollectionRelation::where('collection_id', $this->params['collection_id'])
            ->column('template_id');
        $rows = SwapTemplate::where($this->searchWhere)
            ->whereNotIn('id', $excludeIds)
            ->where('is_collection', 0)
            ->where('status', 1)
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['id' => 'desc'])
            ->select()
            ->toArray();

        return $rows;
    }

    public function count(): int
    {
        $excludeIds = SwapTemplateCollectionRelation::where('collection_id', $this->params['collection_id'])
            ->column('template_id');
        return SwapTemplate::where($this->searchWhere)
            ->whereNotIn('id', $excludeIds)
            ->where('is_collection', 0)
            ->where('status', 1)
            ->count();
    }

}
