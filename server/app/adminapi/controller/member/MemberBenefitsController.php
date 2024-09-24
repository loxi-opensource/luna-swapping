<?php
// +----------------------------------------------------------------------
// | likeshop开源商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop系列产品在gitee、github等公开渠道开源版本可免费商用，未经许可不能去除前后端官方版权标识
// |  likeshop系列产品收费版本务必购买商业授权，购买去版权授权后，方可去除前后端官方版权标识
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | likeshop团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshop.cn.team
// +----------------------------------------------------------------------

namespace app\adminapi\controller\member;


use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\member\MemberBenefitsLists;
use app\adminapi\logic\member\MemberBenefitsLogic;
use app\adminapi\validate\member\MemberBenefitsValidate;

class MemberBenefitsController extends BaseAdminController
{
    /**
     * @notes 会员权益列表
     * @return mixed
     * @author ljj
     * @date 2023/6/27 10:44 上午
     */
    public function lists()
    {
        return $this->dataLists(new MemberBenefitsLists());
    }

    /**
     * @notes 添加会员权益
     * @return mixed
     * @author ljj
     * @date 2023/6/27 10:50 上午
     */
    public function add()
    {
        $params = (new MemberBenefitsValidate())->post()->goCheck('add');
        (new MemberBenefitsLogic())->add($params);
        return $this->success('操作成功',[],1,1);
    }

    /**
     * @notes 编辑会员权益
     * @return mixed
     * @author ljj
     * @date 2023/6/27 11:02 上午
     */
    public function edit()
    {
        $params = (new MemberBenefitsValidate())->post()->goCheck('edit');
        (new MemberBenefitsLogic())->edit($params);
        return $this->success('操作成功',[],1,1);
    }

    /**
     * @notes 会员权益详情
     * @return mixed
     * @author ljj
     * @date 2023/6/27 11:04 上午
     */
    public function detail()
    {
        $params = (new MemberBenefitsValidate())->get()->goCheck('detail');
        $result = (new MemberBenefitsLogic())->detail($params);
        return $this->success('',$result);
    }

    /**
     * @notes 删除会员权益
     * @return mixed
     * @author ljj
     * @date 2023/6/27 11:05 上午
     */
    public function del()
    {
        $params = (new MemberBenefitsValidate())->post()->goCheck('del');
        (new MemberBenefitsLogic())->del($params);
        return $this->success('操作成功',[],1,1);
    }

    /**
     * @notes 调整状态
     * @return mixed
     * @author ljj
     * @date 2023/6/27 11:07 上午
     */
    public function status()
    {
        $params = (new MemberBenefitsValidate())->post()->goCheck('status');
        (new MemberBenefitsLogic())->status($params);
        return $this->success('操作成功',[],1,1);
    }
}