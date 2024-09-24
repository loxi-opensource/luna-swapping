<?php

namespace app\adminapi\lists\swap_template;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\enum\SwapTemplateEnum;
use app\common\lists\ListsSearchInterface;
use app\common\model\swap_template\SwapStrategy;
use app\common\model\swap_template\SwapStrategyGroupRelation;
use app\common\model\swap_template\SwapTemplateGroup;

// 不在指定玩法中的模板分组列表
class NotInStrategyGroupLists extends BaseAdminDataLists implements ListsSearchInterface
{
    public function setSearch(): array
    {
        return [
            '%like%' => ['name'],
            'in' => ['id'],
        ];
    }

    private function baseQuery()
    {
        $strategyId = $this->params['strategy_id'];
        $strategy = SwapStrategy::find($strategyId);
        if (!$strategy) {
            return false;
        }
        if ($strategy->template_scope) {
            $this->searchWhere['is_collection'] = intval($strategy->template_scope
                == SwapTemplateEnum::TEMPLATE_SCOPE_LIMIT_COLLECTION);
        }
        $this->searchWhere['is_group_swap'] = $strategy->is_group_swap;

        $excludeIds = SwapStrategyGroupRelation::where('strategy_id', $this->params['strategy_id'])
            ->column('group_id');
        return SwapTemplateGroup::alias('stg')
            ->whereNotIn('id', $excludeIds)
            ->where($this->searchWhere);
    }

    public function lists(): array
    {
        if ($this->baseQuery()) {
            $rows = $this->baseQuery()
                ->limit($this->limitOffset, $this->limitLength)
                ->order(['stg.id' => 'desc'])
                ->field('stg.*')
                ->select()
                ->toArray();
            return $rows;
        }
        return [];
    }

    public function count(): int
    {
        if ($this->baseQuery()) {
            return $this->baseQuery()->count();
        }
        return 0;
    }

}
