<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------

namespace app\common\logic;

use app\common\enum\MemberPackageEnum;
use app\common\enum\PayEnum;
use app\common\enum\QueueNameConst;
use app\common\enum\user\AccountLogEnum;
use app\common\model\member\MemberOrder;
use app\common\model\recharge\RechargeOrder;
use app\common\model\user\User;
use app\common\model\user\UserMember;
use app\common\utils\LogUtils;
use think\facade\Db;
use think\facade\Log;

/**
 * 支付成功后处理订单状态
 * Class PayNotifyLogic
 * @package app\api\logic
 */
class PayNotifyLogic extends BaseLogic
{

    public static function handle($action, $orderSn, $extra = [])
    {
        Db::startTrans();
        try {
            self::$action($orderSn, $extra);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            Log::write(implode('-', [
                __CLASS__,
                __FUNCTION__,
                $e->getFile(),
                $e->getLine(),
                $e->getMessage()
            ]));
            self::setError($e->getMessage());
            return $e->getMessage();
        }
    }


    /**
     * @notes 充值回调
     * @param $orderSn
     * @param array $extra
     * @author 段誉
     * @date 2023/2/27 15:28
     */
    public static function recharge($orderSn, $extra = [])
    {
        $order = RechargeOrder::where('sn', $orderSn)->findOrEmpty();
        $recharge_package = $order->recharge_package_info;
        // 增加用户累计充值金额及用户余额
        $user = User::findOrEmpty($order->user_id);
        $isFree = $order->is_free == 1;
        if (!$isFree) {
            $user->total_amount += $order->order_amount;
        }

        $user->balance_draw += $order->draw_number;
        $user->save();

        // 记录账户流水
        if (isset($recharge_package['draw_number']) && $recharge_package['draw_number'] > 0) {
            AccountLogLogic::add(
                $order->user_id,
                AccountLogEnum::DRAW_INC_RECHARGE,
                AccountLogEnum::INC,
                $recharge_package['draw_number'],
                $order->sn
            );
        }
        if (isset($recharge_package['is_give']) && $recharge_package['is_give'] == 1 && isset($recharge_package['give_draw_number']) && $recharge_package['give_draw_number'] > 0) {
            AccountLogLogic::add(
                $order->user_id,
                AccountLogEnum::DRAW_INC_RECHARGE_GIVE,
                AccountLogEnum::INC,
                $recharge_package['give_draw_number'],
                $order->sn
            );
        }

        // 更新充值订单状态
        $order->transaction_id = $extra['transaction_id'] ?? "";
        $order->pay_status = PayEnum::ISPAID;
        $order->pay_time = time();
        $order->save();

        // 入队列：支付成功后置操作
        $job = "app\\job\\OnPaySuccessJob";
        \think\facade\Queue::push($job, [
            'orderID' => $order->id,
            'terminal' => $order->terminal,
            "from" => "recharge",
        ], QueueNameConst::QueuePaySuccess);
    }


    /**
     * @notes 会员订单支付回调
     * @param $orderSn
     * @param array $extra
     * @author ljj
     * @date 2023/4/20 6:27 下午
     */
    public static function member($orderSn, $extra = [])
    {
        // TODO 事务操作
        $order = MemberOrder::where('sn', $orderSn)->findOrEmpty();
        $member_package = json_decode($order->member_package_info, true);
        $user = User::findOrEmpty($order->user_id);

        //增加用户会员到期时间
        $time = time();
        $add_time = $member_package['duration_days'] * 24 * 60 * 60; //增加的时间

        $member_end_time = $time + $add_time;
        $user->member_end_time = $member_end_time;
        $user->total_amount += $order->order_amount;
        $user->save();

        // 更新订单支付状态
        $order->transaction_id = $extra['transaction_id'] ?? "";
        $order->pay_status = PayEnum::ISPAID;
        $order->pay_time = $time;
        $order->save();

        //添加会员开通记录
        UserMember::create([
            'user_id' => $order->user_id,
            'operate_id' => $order->user_id,
            'order_id' => $order->id,
            'channel' => MemberPackageEnum::CHANNEL_USER,
            'package_name' => $member_package['name'],
            'member_end_time' => $user->member_end_time,
            'is_perpetual' => 0,
            'package_info' => $order->member_package_info,
            'add_member_time' => $add_time ?? null,
        ]);
    }

}
