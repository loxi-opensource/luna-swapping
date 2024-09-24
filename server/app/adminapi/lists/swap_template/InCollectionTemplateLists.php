<?php

namespace app\adminapi\lists\swap_template;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\lists\ListsSearchInterface;
use app\common\model\swap_template\SwapTemplate;

// 指定合辑中的模板列表
class InCollectionTemplateLists extends BaseAdminDataLists implements ListsSearchInterface
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
            ->join('swap_template_collection_relation stcr', 'st.id = stcr.template_id')
            ->where('stcr.collection_id', $this->params['id'])
            ->where($this->searchWhere)
            ->limit($this->limitOffset, $this->limitLength)
            ->field(['st.*', 'stcr.id as relation_id', 'stcr.create_time as create_time'])
            ->order(['stcr.id desc'])
            ->select()
            ->toArray();

        return $rows;
    }

    public function count(): int
    {
        return SwapTemplate::alias('st')
            ->join('swap_template_collection_relation stcr', 'st.id = stcr.template_id')
            ->where('stcr.collection_id', $this->params['id'])
            ->where($this->searchWhere)->count();
    }

}
