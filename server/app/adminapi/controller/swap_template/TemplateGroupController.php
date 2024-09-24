<?php

namespace app\adminapi\controller\swap_template;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\swap_template\InGroupTemplateLists;
use app\adminapi\lists\swap_template\NotInGroupTemplateLists;
use app\adminapi\lists\swap_template\TemplateGroupLists;
use app\common\model\swap_template\SwapTemplateGroup;
use app\common\model\swap_template\SwapTemplateGroupRelation;
use think\helper\Arr;

class TemplateGroupController extends BaseAdminController
{
    public function lists()
    {
        return $this->dataLists(new TemplateGroupLists());
    }

    public function detail()
    {
        $id = $this->request->get('id');
        $detail = SwapTemplateGroup::find($id)->toArray();
        return $this->success('success', $detail);
    }

    public function add()
    {
        $params = $this->request->post();
        $result = SwapTemplateGroup::create($params);
        return $this->success('操作成功', [], 1, 1);
    }

    public function edit()
    {
        $params = $this->request->post();
        $toUpdate = Arr::only($params, [
            'name', 'status'
        ]);
        SwapTemplateGroup::update($toUpdate, ['id' => $params['id']]);
        return $this->success('操作成功', [], 1, 1);
    }

    // 查询当前模板组中的模板
    public function inGroupTemplateLists()
    {
        return $this->dataLists(new InGroupTemplateLists());
    }

    // 查询不在当前模板组中的模板
    public function notInGroupTemplateLists()
    {
        return $this->dataLists(new NotInGroupTemplateLists());
    }

    public function editRelation()
    {
        $relationId = $this->request->post('relation_id');
        $sort = $this->request->post('sort');
        $relation = SwapTemplateGroupRelation::find($relationId);
        $relation->sort = $sort;
        $relation->save();
        return $this->success();
    }

    public function addTemplates()
    {
        $params = $this->request->post();
        foreach ($params['template_ids'] as $templateId) {
            $relation = SwapTemplateGroupRelation::where([
                'group_id' => $params['group_id'],
                'template_id' => $templateId
            ])->find();
            if ($relation) {
                continue;
            }
            SwapTemplateGroupRelation::create([
                'group_id' => $params['group_id'],
                'template_id' => $templateId
            ]);
        }
        return $this->success('操作成功', [], 1, 1);
    }

    public function removeTemplate()
    {
        SwapTemplateGroupRelation::destroy($this->request->post('relation_id'));
        return $this->success('操作成功', [], 1, 1);
    }
}
