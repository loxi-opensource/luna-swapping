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

namespace app\adminapi\logic\finance;


use app\common\enum\PayEnum;
use app\common\logic\BaseLogic;
use app\common\model\member\MemberOrder;
use app\common\model\recharge\RechargeOrder;
use app\common\model\user\User;

class FinanceLogic extends BaseLogic
{
    /**
     * @notes 财务中心
     * @return array[]
     * @author ljj
     * @date 2023/4/25 5:56 下午
     */
    public function center()
    {
        $member_order_num = MemberOrder::where(['pay_status'=>PayEnum::ISPAID])->count();
        $member_total_amount = MemberOrder::where(['pay_status'=>PayEnum::ISPAID])->sum('order_amount');
        $member_refund_order_num = MemberOrder::where(['pay_status'=>PayEnum::ISPAID,'refund_status'=>PayEnum::REFUND_SUCCESS])->count();
        $member_refund_total_amount = MemberOrder::where(['pay_status'=>PayEnum::ISPAID,'refund_status'=>PayEnum::REFUND_SUCCESS])->sum('order_amount');
        $member_net_income = $member_total_amount - $member_refund_total_amount;

        $recharge_order_num = RechargeOrder::where(['pay_status'=>PayEnum::ISPAID])->count();
        $recharge_total_amount = RechargeOrder::where(['pay_status'=>PayEnum::ISPAID])->sum('order_amount');
        $recharge_refund_order_num = RechargeOrder::where(['pay_status'=>PayEnum::ISPAID,'refund_status'=>PayEnum::REFUND_SUCCESS])->count();
        $recharge_refund_total_amount = RechargeOrder::where(['pay_status'=>PayEnum::ISPAID,'refund_status'=>PayEnum::REFUND_SUCCESS])->sum('order_amount');
        $recharge_net_income = $recharge_total_amount - $recharge_refund_total_amount;

        return [
            'business_overview' => [
                'total_amount' => round($member_total_amount + $recharge_total_amount,2),
                'total_num' => $member_order_num + $recharge_order_num,
                'total_refund_amount' => round($member_refund_total_amount + $recharge_refund_total_amount,2),
                'total_refund_num' => $member_refund_order_num + $recharge_refund_order_num,
                'net_income' => round($member_net_income + $recharge_net_income,2),
            ],
            'order_overview' => [
                'total_member_amount' => $member_total_amount,
                'total_member_num' => $member_order_num,
                'total_member_refund_amount' => $member_refund_total_amount,
                'total_member_refund_num' => $member_refund_order_num,
                'member_net_income' => round($member_net_income,2),

                'total_recharge_amount' => $recharge_total_amount,
                'total_recharge_num' => $recharge_order_num,
                'total_recharge_refund_amount' => $recharge_refund_total_amount,
                'total_recharge_refund_num' => $recharge_refund_order_num,
                'recharge_net_income' => round($recharge_net_income,2),
            ],
            'user_overview' => [
                'user_num' => User::count(),
                'member_user_num' => User::where('member_end_time','>',0)->whereOr('member_perpetual', '=', 1)->count(),
                'user_total_amount' => User::sum('total_amount'),
                'total_recharge_num' => RechargeOrder::where(['pay_status'=>PayEnum::ISPAID])->group('user_id')->count(),
                'user_balance' => User::sum('balance'),
                'user_balance_draw' => User::sum('balance_draw'),
                'user_total_quiz' => User::sum('total_quiz'),
                'user_total_draw' => User::sum('total_draw'),
            ],
        ];
    }
}