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

namespace app\adminapi\validate\recharge;


use app\common\enum\PayEnum;
use app\common\model\recharge\RechargeOrder;
use app\common\validate\BaseValidate;

class RechargeOrderValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require',
    ];

    protected $message = [
        'id.require' => '参数缺失',
    ];


    public function sceneDetail()
    {
        return $this->only(['id']);
    }

    public function sceneRefund()
    {
        return $this->only(['id'])
            ->append('id','checkRefund');
    }


    /**
     * @notes 校验退款
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2023/4/19 4:09 下午
     */
    public function checkRefund($value,$rule,$data)
    {
        $result = RechargeOrder::where(['id'=>$value])->findOrEmpty();
        if ($result->isEmpty()) {
            return '充值订单错误';
        }
        if ($result->pay_status != PayEnum::ISPAID) {
            return '充值订单支付状态错误，无法退款';
        }
        if ($result->refund_status != PayEnum::REFUND_NOT) {
            return '充值订单退款状态错误，无法退款';
        }

        return true;
    }
}