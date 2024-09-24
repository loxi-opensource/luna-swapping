<?php

namespace app\adminapi\lists\swap_template;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\lists\ListsSearchInterface;
use app\common\model\swap_template\SwapStrategy;
use app\common\model\swap_template\SwapTemplateGroup;

class StrategyLists extends BaseAdminDataLists implements ListsSearchInterface
{

    public function setSearch(): array
    {
        return [
            '=' => ['allow_cross_group', 'is_group_swap'],
            '%like%' => ['name'],
        ];
    }

    public function lists(): array
    {
        $rows = SwapStrategy::where($this->searchWhere)
            ->with(['groups'])
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['id' => 'desc'])
            ->select()
            ->toArray();
        return $rows;
    }

    public function count(): int
    {
        return SwapStrategy::where($this->searchWhere)->count();
    }

}
