<?php

namespace app\adminapi\lists\swap_template;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\lists\ListsSearchInterface;
use app\common\model\swap_template\SwapTemplate;

class TemplateLists extends BaseAdminDataLists implements ListsSearchInterface
{

    public function setSearch(): array
    {
        return [
            '=' => ['is_collection', 'up_template_id', 'is_official'],
            '%like%' => ['name'],
        ];
    }

    public function lists(): array
    {
        $rows = SwapTemplate::where($this->searchWhere)
            ->with(['template_groups'])
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['id' => 'desc'])
            ->select()
            ->toArray();
        return $rows;
    }

    public function count(): int
    {
        return SwapTemplate::where($this->searchWhere)->count();
    }

}
