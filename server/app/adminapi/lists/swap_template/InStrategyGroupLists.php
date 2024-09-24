<?php

namespace app\adminapi\lists\swap_template;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\lists\ListsSearchInterface;
use app\common\model\swap_template\SwapTemplate;
use app\common\model\swap_template\SwapTemplateGroup;

// 指定玩法中的模板分组列表
class InStrategyGroupLists extends BaseAdminDataLists implements ListsSearchInterface
{
    public function setSearch(): array
    {
        return [
            '%like%' => ['name'],
        ];
    }

    public function lists(): array
    {
        $rows = SwapTemplateGroup::alias('stg')
            ->join('swap_strategy_group_relation ssgr', 'stg.id = ssgr.group_id')
            ->where('ssgr.strategy_id', $this->params['id'])
            ->where($this->searchWhere)
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['ssgr.sort' => 'desc', 'ssgr.id' => 'desc'])
            ->field(['stg.*', 'ssgr.id as relation_id', 'ssgr.sort', 'ssgr.create_time as create_time'])
            ->select()
            ->toArray();

        return $rows;
    }

    public function count(): int
    {
        return SwapTemplateGroup::alias('stg')
            ->join('swap_strategy_group_relation ssgr', 'stg.id = ssgr.group_id')
            ->where('ssgr.strategy_id', $this->params['id'])
            ->where($this->searchWhere)->count();
    }

}
