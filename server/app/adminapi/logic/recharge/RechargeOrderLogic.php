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

namespace app\adminapi\logic\recharge;


use app\common\enum\PayEnum;
use app\common\enum\RefundEnum;
use app\common\enum\user\AccountLogEnum;
use app\common\logic\AccountLogLogic;
use app\common\logic\BaseLogic;
use app\common\logic\RefundLogic;
use app\common\model\recharge\RechargeOrder;
use app\common\model\refund\RefundRecord;
use app\common\model\user\User;
use think\facade\Db;

class RechargeOrderLogic extends BaseLogic
{
    /**
     * @notes 充值订单详情
     * @param $params
     * @return array
     * @author ljj
     * @date 2023/4/19 3:43 下午
     */
    public function detail($params)
    {
        $result = RechargeOrder::where(['id'=>$params['id']])
            ->with(['user'])
            ->append(['terminal_text','order_type_text','pay_status_text','pay_way_text','pay_time_text','refund_status_text','recharge_package_name'])
            ->findOrEmpty()
            ->toArray();

        return $result;
    }

    /**
     * @notes 退款
     * @param $params
     * @param $adminId
     * @return bool|string
     * @author ljj
     * @date 2023/4/19 4:23 下午
     */
    public static function refund($params, $adminId)
    {
        Db::startTrans();
        try {
            $order = RechargeOrder::findOrEmpty($params['id'])->toArray();
            $user = User::findOrEmpty($order['user_id'])->toArray();

            // 更新订单信息, 标记已发起退款状态,具体退款成功看退款日志
            RechargeOrder::update([
                'id' => $order['id'],
                'refund_status' => PayEnum::REFUND_SUCCESS,
            ]);

            //扣减用户余额和累计消费金额
            if ($user['balance'] > $order['number']) {
                $number = $order['number'];
            } else {
                $number = $user['balance'];
            }
            if ($user['balance_draw'] > $order['draw_number']) {
                $number_draw = $order['draw_number'];
            } else {
                $number_draw = $user['balance_draw'];
            }
            User::update(['balance'=>['dec',$number],'balance_draw'=>['dec',$number_draw]],['id'=>$user['id']]);

            // 记录账户流水
            if ($number > 0) {
                AccountLogLogic::add(
                    $order['user_id'],
                    AccountLogEnum::UM_DEC_RECHARGE_REFUND,
                    AccountLogEnum::DEC,
                    $number,
                    $order['sn'],
                    '充值订单退款'
                );
            }
            if ($number_draw > 0) {
                AccountLogLogic::add(
                    $order['user_id'],
                    AccountLogEnum::DRAW_DEC_RECHARGE_REFUND,
                    AccountLogEnum::DEC,
                    $number_draw,
                    $order['sn'],
                    '充值订单退款'
                );
            }


            // 生成退款记录
            $recordSn = generate_sn(RefundRecord::class, 'sn');
            $record = RefundRecord::create([
                'sn' => $recordSn,
                'user_id' => $order['user_id'],
                'order_id' => $order['id'],
                'order_sn' => $order['sn'],
                'order_type' => RefundEnum::ORDER_TYPE_RECHARGE,
                'order_amount' => $order['order_amount'],
                'refund_amount' => $order['order_amount'],
                'refund_type' => RefundEnum::TYPE_ADMIN,
                'transaction_id' => $order['transaction_id'] ?? '',
                'refund_way' => RefundEnum::getRefundWayByPayWay($order['pay_way']),
            ]);

            // 退款
            RefundLogic::refund($order, $record['id'], $order['order_amount'], $adminId);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
    }
}