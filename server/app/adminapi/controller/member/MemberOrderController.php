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
use app\adminapi\lists\member\MemberOrderLists;
use app\adminapi\logic\member\MemberOrderLogic;
use app\adminapi\validate\member\MemberOrderValidate;

class MemberOrderController extends BaseAdminController
{
    /**
     * @notes 会员订单列表
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/21 10:09 上午
     */
    public function lists()
    {
        return $this->dataLists(new MemberOrderLists());
    }

    /**
     * @notes 会员订单详情
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/21 10:12 上午
     */
    public function detail()
    {
        $params = (new MemberOrderValidate())->get()->goCheck('detail');
        $result = (new MemberOrderLogic())->detail($params);
        return $this->success('',$result);
    }

    /**
     * @notes 退款
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/21 10:33 上午
     */
    public function refund()
    {
        $params = (new MemberOrderValidate())->post()->goCheck('refund');
        $result = (new MemberOrderLogic())->refund($params, $this->adminId);
        if(true !== $result) {
            return $this->fail($result);
        }
        return $this->success('操作成功', [], 1, 1);
    }
}