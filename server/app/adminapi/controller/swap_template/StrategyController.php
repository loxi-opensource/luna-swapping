<?php

namespace app\adminapi\controller\swap_template;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\swap_template\InGroupTemplateLists;
use app\adminapi\lists\swap_template\InStrategyGroupLists;
use app\adminapi\lists\swap_template\NotInGroupTemplateLists;
use app\adminapi\lists\swap_template\NotInStrategyGroupLists;
use app\adminapi\lists\swap_template\StrategyLists;
use app\adminapi\lists\swap_template\TemplateGroupLists;
use app\common\model\swap_template\SwapStrategy;
use app\common\model\swap_template\SwapStrategyGroupRelation;
use think\helper\Arr;

class StrategyController extends BaseAdminController
{
    public function lists()
    {
        return $this->dataLists(new StrategyLists());
    }

    public function detail()
    {
        $id = $this->request->get('id');
        $detail = SwapStrategy::find($id)->toArray();
        $detail['group_cnt'] = SwapStrategyGroupRelation::where('strategy_id', $id)->count();
        return $this->success('success', $detail);
    }

    public function add()
    {
        $params = $this->request->post();
        $result = SwapStrategy::create($params);
        return $this->success('操作成功', [], 1, 1);
    }

    public function edit()
    {
        $params = $this->request->post();
        $toUpdate = Arr::only($params, [
            'name', 'status'
        ]);
        SwapStrategy::update($toUpdate, ['id' => $params['id']]);
        return $this->success('操作成功', [], 1, 1);
    }

    // 查询当前玩法中的模板组
    public function inStrategyGroupLists()
    {
        return $this->dataLists(new InStrategyGroupLists());
    }

    // 查询不在当前玩法中的模板组
    public function notInStrategyGroupLists()
    {
        return $this->dataLists(new NotInStrategyGroupLists());
    }

    public function editRelation()
    {
        $relationId = $this->request->post('relation_id');
        $sort = $this->request->post('sort');
        $relation = SwapStrategyGroupRelation::find($relationId);
        $relation->sort = $sort;
        $relation->save();
        return $this->success();
    }

    public function addGroups()
    {
        $params = $this->request->post();
        foreach ($params['group_ids'] as $templateId) {
            $relation = SwapStrategyGroupRelation::where([
                'strategy_id' => $params['strategy_id'],
                'group_id' => $templateId
            ])->find();
            if ($relation) {
                continue;
            }
            SwapStrategyGroupRelation::create([
                'strategy_id' => $params['strategy_id'],
                'group_id' => $templateId
            ]);
        }
        return $this->success('操作成功', [], 1, 1);
    }

    public function removeGroup()
    {
        SwapStrategyGroupRelation::destroy($this->request->post('relation_id'));
        return $this->success('操作成功', [], 1, 1);
    }
}
