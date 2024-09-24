<?php

namespace app\adminapi\lists\swap_template;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\lists\ListsSearchInterface;
use app\common\model\swap_template\SwapTemplate;

// 指定分组中的模板列表
class InGroupTemplateLists extends BaseAdminDataLists implements ListsSearchInterface
{
    public function setSearch(): array
    {
        return [
            '%like%' => ['name'],
        ];
    }

    public function lists(): array
    {
        $rows = SwapTemplate::alias('st')
            ->join('swap_template_group_relation stgr', 'st.id = stgr.template_id')
            ->where('stgr.group_id', $this->params['id'])
            ->where($this->searchWhere)
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['stgr.sort' => 'desc', 'stgr.id' => 'desc'])
            ->field(['st.*', 'stgr.id as relation_id', 'stgr.sort', 'stgr.create_time as create_time'])
            ->select()
            ->toArray();

        return $rows;
    }

    public function count(): int
    {
        return SwapTemplate::alias('st')
            ->join('swap_template_group_relation stgr', 'st.id = stgr.template_id')
            ->where('stgr.group_id', $this->params['id'])
            ->where($this->searchWhere)->count();
    }

}
