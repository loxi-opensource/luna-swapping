<?php
// +----------------------------------------------------------------------
// | likeshop100%开源免费商用商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshopTeam
// +----------------------------------------------------------------------

namespace app\adminapi\validate\draw;

use app\adminapi\logic\draw\DrawCategoryLogic;
use app\common\model\draw\DrawCategory;
use app\common\model\draw\DrawPrompt;
use app\common\validate\BaseValidate;

/**
 * DrawCategory验证器
 * Class DrawCategoryValidate
 * @package app\adminapi\validate\draw
 */
class DrawCategoryValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require',
        'pid' => 'require',
        'name' => 'require',
        'status' => 'require',
    ];

    protected $field = [
        'id' => 'id',
        'pid' => '父级分类',
        'name' => '分类名称',
        'status' => '状态',
    ];


    public function sceneAdd()
    {
        return $this->only(['pid', 'name', 'status'])
            ->append("pid", "checkAddPid");
    }

    public function sceneEdit()
    {
        return $this->only(['id', 'pid', 'name', 'status'])
            ->append("pid", "checkEditPid");
    }

    public function sceneDelete()
    {
        return $this->only(['id'])->append("id", "checkDelete");
    }

    public function sceneId()
    {
        return $this->only(['id']);
    }

    /**
     * @notes 校验父级id
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author 段誉
     * @date 2023/6/14 19:00
     */
    public function checkAddPid($value, $rule, $data)
    {
        if ($value == 0) {
            return true;
        }

        $partner = DrawCategory::where('id', $value)->findOrEmpty();
        if ($partner->isEmpty()) {
            return '父级分类不存在';
        }

        if ($partner['level'] > 1) {
            return "只能添加2级分类";
        }

        return true;
    }

    /**
     * @notes 校验父级分类
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author 段誉
     * @date 2023/6/14 17:21
     */
    public function checkEditPid($value, $rule, $data)
    {
        $cate = DrawCategory::findOrEmpty($data['id']);
        if ($cate->isEmpty()) {
            return "当前分类信息不存在";
        }

        if ($value == 0) {
            return true;
        }

        $partner = DrawCategory::where('id', $value)->findOrEmpty();
        if ($partner->isEmpty()) {
            return '父级分类不存在';
        }

        if ($cate['id'] == $partner['id']) {
            return "父级分类不可是当前分类";
        }

        if ($partner['level'] > 1) {
            return "只能添加2级分类";
        }

        return true;
    }

    /**
     * @notes 删除校验
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author 段誉
     * @date 2023/6/15 9:46
     */
    public function checkDelete($value, $rule, $data) {
        $cate = DrawCategory::where(['pid' => $value])->findOrEmpty();
        if (!$cate->isEmpty()) {
            return "已有关联子级分类，不可删除";
        }

        $prompt = DrawPrompt::where('category_id', $value)->findOrEmpty();
        if (!$prompt->isEmpty()) {
            return "关键词分类已使用， 需移除分类关联关键词后再作删除";
        }

        return true;
    }
}