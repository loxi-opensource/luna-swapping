<?php

namespace app\adminapi\lists\swap_template;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\lists\ListsSearchInterface;
use app\common\model\swap_template\SwapTemplateGroup;

class TemplateGroupLists extends BaseAdminDataLists implements ListsSearchInterface
{

    public function setSearch(): array
    {
        return [
            '=' => ['is_collection', 'is_group_swap'],
            '%like%' => ['name'],
        ];
    }

    public function lists(): array
    {
        $rows = SwapTemplateGroup::where($this->searchWhere)
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['id' => 'desc'])
            ->select()
            ->toArray();
        return $rows;
    }

    public function count(): int
    {
        return SwapTemplateGroup::where($this->searchWhere)->count();
    }

}
