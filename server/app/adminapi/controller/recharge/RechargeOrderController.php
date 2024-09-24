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

namespace app\adminapi\controller\recharge;


use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\recharge\RechargeOrderLists;
use app\adminapi\logic\recharge\RechargeOrderLogic;
use app\adminapi\validate\recharge\RechargeOrderValidate;

class RechargeOrderController extends BaseAdminController
{
    /**
     * @notes 充值订单列表
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/19 3:05 下午
     */
    public function lists()
    {
        return $this->dataLists(new RechargeOrderLists());
    }

    /**
     * @notes 充值订单详情
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/19 3:43 下午
     */
    public function detail()
    {
        $params = (new RechargeOrderValidate())->get()->goCheck('detail');
        $result = (new RechargeOrderLogic())->detail($params);
        return $this->success('',$result);
    }

    /**
     * @notes 退款
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/19 4:23 下午
     */
    public function refund()
    {
        $params = (new RechargeOrderValidate())->post()->goCheck('refund');
        $result = (new RechargeOrderLogic())->refund($params, $this->adminId);
        if(true !== $result) {
            return $this->fail($result);
        }
        return $this->success('操作成功', [], 1, 1);
    }
}
