<?php

namespace app\adminapi\controller\swap_template;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\swap_template\InCollectionTemplateLists;
use app\adminapi\lists\swap_template\NotInCollectionTemplateLists;
use app\adminapi\lists\swap_template\TemplateLists;
use app\common\model\swap_template\SwapTemplate;
use app\common\model\swap_template\SwapTemplateCollectionRelation;
use think\helper\Arr;

class TemplateController extends BaseAdminController
{
    public function lists()
    {
        return $this->dataLists(new TemplateLists());
    }

    // 查询合辑模板的子模版列表
    public function inCollectionTemplateLists()
    {
        // 请求参数
        // id: 合辑模板id
        return $this->dataLists(new InCollectionTemplateLists());
    }

    // 查询不在当前合辑模板中的模板
    public function notInCollectionTemplateLists()
    {
        // 请求参数
        // id: 合辑模板id
        return $this->dataLists(new NotInCollectionTemplateLists());
    }

    public function detail()
    {
        $templateId = $this->request->get('id');
        $detail = SwapTemplate::find($templateId)->toArray();
        $detail['children_cnt'] = SwapTemplateCollectionRelation::where(
            'collection_id', $templateId)->count();
        return $this->success('success', $detail);
    }

    public function add()
    {
        $params = $this->request->post();
        $result = SwapTemplate::create($params);
        return $this->success('操作成功',[],1,1);
    }

    public function edit()
    {
        $params = $this->request->post();
        $toUpdate = Arr::only($params, [
            'name', 'status', 'image_url'
        ]);
        SwapTemplate::update($toUpdate, ['id' => $params['id']]);
        return $this->success('操作成功',[],1,1);
    }

    public function addChild()
    {
        $params = $this->request->post();
        foreach ($params['template_ids'] as $templateId) {
            $relation = SwapTemplateCollectionRelation::where([
                'collection_id' => $params['collection_id'],
                'template_id' => $templateId
            ])->find();
            if ($relation) {
                continue;
            }
            SwapTemplateCollectionRelation::create([
                'collection_id' => $params['collection_id'],
                'template_id' => $templateId
            ]);
        }
        return $this->success('操作成功',[],1,1);
    }

    public function removeChild()
    {
        SwapTemplateCollectionRelation::destroy($this->request->post('relation_id'));
        return $this->success('操作成功',[],1,1);
    }

}
