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

namespace app\common\enum\user;

/**
 * 用户账户流水变动表枚举
 * Class AccountLogEnum
 * @package app\common\enum
 */
class AccountLogEnum
{
    /**
     * 变动对象
     * UM 对话余额(balance)
     * MONEY 可提现佣金(user_money)
     * DRAW 绘画余额(balance_draw)
     */
    const UM = 1;
    const MONEY = 2;
    const DRAW = 3;

    /**
     * 动作
     * INC 增加
     * DEC 减少
     */
    const INC = 1;
    const DEC = 2;


    /**
     * 对话余额减少类型
     */
    const UM_DEC_ADMIN = 100;
    const UM_DEC_RECHARGE_REFUND = 101;
    const UM_DEC_CHAT    = 102;
    const UM_DEC_DRAW    = 103;
    const UM_DEC_MEMBER_GIVE = 104;

    /**
     * 对话余额增加类型
     */
    const UM_INC_ADMIN = 200;
    const UM_INC_RECHARGE = 201;
    const UM_INC_SHARE = 202;
    const UM_INC_INVITE = 203;
    const UM_INC_REGISTER = 204;
    const UM_INC_DRAW_FAIL = 205;
    const UM_INC_RECHARGE_GIVE = 206;
    const UM_INC_CARDCODE_GIVE = 207;
    const UM_INC_MEMBER_GIVE = 208;


    /**
     * 可提现佣金减少类型
     */
    const MONEY_DEC_WITHDRAW = 300;

    /**
     * 可提现佣金增加类型
     */
    const MONEY_INC_WITHDRAW_FAIL = 400;
    const MONEY_INC_DISTRIBUTION = 401;


    /**
     * 绘画余额减少类型
     */
    const DRAW_DEC_ADMIN = 500;
    const DRAW_DEC_RECHARGE_REFUND = 501;
    const DRAW_DEC_IMAGE = 502;
    const DRAW_DEC_MEMBER_GIVE = 503;

    /**
     * 绘画余额增加类型
     */
    const DRAW_INC_ADMIN = 600;
    const DRAW_INC_RECHARGE = 601;
    const DRAW_INC_DRAW_FAIL = 602;
    const DRAW_INC_RECHARGE_GIVE = 603;
    const DRAW_INC_REGISTER = 604;
    const DRAW_INC_MEMBER_GIVE = 605;
    const DRAW_INC_CARDCODE_GIVE = 606;
    const DRAW_INC_SUBSCRIBE_GIVE = 607;


    /**
     * 对话余额（减少类型汇总）
     */
    const UM_DEC = [
        self::UM_DEC_ADMIN,
        self::UM_DEC_RECHARGE_REFUND,
        self::UM_DEC_CHAT,
        self::UM_DEC_DRAW,
        self::UM_DEC_MEMBER_GIVE,
    ];


    /**
     * 对话余额（增加类型汇总）
     */
    const UM_INC = [
        self::UM_INC_ADMIN,
        self::UM_INC_RECHARGE,
        self::UM_INC_SHARE,
        self::UM_INC_INVITE,
        self::UM_INC_REGISTER,
        self::UM_INC_DRAW_FAIL,
        self::UM_INC_RECHARGE_GIVE,
        self::UM_INC_CARDCODE_GIVE,
        self::UM_INC_MEMBER_GIVE
    ];


    /**
     * 可提现佣金（减少类型汇总）
     */
    const MONEY_DEC = [
        self::MONEY_DEC_WITHDRAW,
    ];


    /**
     * 可提现佣金（增加类型汇总）
     */
    const MONEY_INC = [
        self::MONEY_INC_WITHDRAW_FAIL,
        self::MONEY_INC_DISTRIBUTION,
    ];


    /**
     * 绘画余额（减少类型汇总）
     */
    const DRAW_DEC = [
        self::DRAW_DEC_ADMIN,
        self::DRAW_DEC_RECHARGE_REFUND,
        self::DRAW_DEC_IMAGE,
        self::DRAW_DEC_MEMBER_GIVE,
    ];


    /**
     * 绘画余额（增加类型汇总）
     */
    const DRAW_INC = [
        self::DRAW_INC_ADMIN,
        self::DRAW_INC_RECHARGE,
        self::DRAW_INC_DRAW_FAIL,
        self::DRAW_INC_RECHARGE_GIVE,
        self::DRAW_INC_REGISTER,
        self::DRAW_INC_MEMBER_GIVE,
        self::DRAW_INC_CARDCODE_GIVE,
        self::DRAW_INC_SUBSCRIBE_GIVE,
    ];


    /**
     * @notes 动作描述
     * @param $action
     * @param false $flag
     * @return string|string[]
     * @author 段誉
     * @date 2023/2/23 10:07
     */
    public static function getActionDesc($action, $flag = false)
    {
        $desc = [
            self::DEC => '减少',
            self::INC => '增加',
        ];
        if ($flag) {
            return $desc;
        }
        return $desc[$action] ?? '';
    }


    /**
     * @notes 变动类型描述
     * @param $changeType
     * @param false $flag
     * @return string|string[]
     * @author 段誉
     * @date 2023/2/23 10:07
     */
    public static function getChangeTypeDesc($changeType, $flag = false)
    {
        $desc = [
            self::UM_DEC_ADMIN => '平台减少余额',
            self::UM_INC_ADMIN => '平台增加余额',
            self::UM_INC_RECHARGE => '用户充值对话余额',
            self::UM_DEC_RECHARGE_REFUND => '充值订单退款减少余额',
            self::UM_INC_SHARE => '分享增加余额',
            self::UM_DEC_CHAT => '对话消耗余额',
            self::UM_DEC_DRAW => '绘画消耗余额',
            self::UM_INC_INVITE => '邀请增加余额',
            self::UM_INC_REGISTER => '注册增加余额',
            self::UM_INC_DRAW_FAIL => '绘画失败退回',
            self::MONEY_DEC_WITHDRAW => '提现扣减佣金',
            self::MONEY_INC_WITHDRAW_FAIL => '提现失败退回佣金',
            self::MONEY_INC_DISTRIBUTION => '分销收益',
            self::DRAW_DEC_ADMIN => '平台减少绘画余额',
            self::DRAW_DEC_RECHARGE_REFUND => '充值退款减少绘画余额',
            self::DRAW_DEC_IMAGE => '绘画减少绘画余额',
            self::DRAW_INC_ADMIN => '平台增加绘画余额',
            self::DRAW_INC_RECHARGE => '用户充值绘画余额',
            self::DRAW_INC_DRAW_FAIL => '绘画失败返回绘画余额',
            self::DRAW_INC_RECHARGE_GIVE => '充值赠送绘画余额',
            self::UM_INC_RECHARGE_GIVE => '充值赠送对话余额',
            self::DRAW_INC_REGISTER => '注册赠送绘画余额',
            self::DRAW_INC_MEMBER_GIVE => '开通会员赠送绘画次数',
            self::DRAW_DEC_MEMBER_GIVE => '会员退款退回绘画余额',
            self::UM_INC_CARDCODE_GIVE  => '卡密充值对话余额',
            self::DRAW_INC_CARDCODE_GIVE    => '卡密充值绘画余额',
            self::UM_DEC_MEMBER_GIVE => '会员退款退回对话余额',
            self::UM_INC_MEMBER_GIVE  => '开通会员赠送对话次数',
            self::DRAW_INC_SUBSCRIBE_GIVE => '关注公众号赠送作图次数',
        ];
        if ($flag) {
            return $desc;
        }
        return $desc[$changeType] ?? '';
    }


    /**
     * @notes 获取对话余额类型描述
     * @return string|string[]
     * @author 段誉
     * @date 2023/2/23 10:08
     */
    public static function getBalanceChangeTypeDesc()
    {
        $UMChangeType = self::getBalanceChangeType();
        $changeTypeDesc = self::getChangeTypeDesc('', true);
        return array_filter($changeTypeDesc, function ($key) use ($UMChangeType) {
            return in_array($key, $UMChangeType);
        }, ARRAY_FILTER_USE_KEY);
    }


    /**
     * @notes 获取对话余额变动类型
     * @return int[]
     * @author 段誉
     * @date 2023/2/23 10:08
     */
    public static function getBalanceChangeType() : array
    {
        return array_merge(self::UM_DEC, self::UM_INC);
    }


    /**
     * @notes 获取可提现佣金类型描述
     * @return string|string[]
     * @author 段誉
     * @date 2023/2/23 10:08
     */
    public static function getUserMoneyChangeTypeDesc()
    {
        $MONEYChangeType = self::getUserMoneyChangeType();
        $changeTypeDesc = self::getChangeTypeDesc('', true);
        return array_filter($changeTypeDesc, function ($key) use ($MONEYChangeType) {
            return in_array($key, $MONEYChangeType);
        }, ARRAY_FILTER_USE_KEY);
    }


    /**
     * @notes 获取可提现佣金变动类型
     * @return int[]
     * @author 段誉
     * @date 2023/2/23 10:08
     */
    public static function getUserMoneyChangeType() : array
    {
        return array_merge(self::MONEY_DEC, self::MONEY_INC);
    }


    /**
     * @notes 获取对话余额类型描述
     * @return string|string[]
     * @author 段誉
     * @date 2023/2/23 10:08
     */
    public static function getBalanceDrawChangeTypeDesc()
    {
        $UMChangeType = self::getBalanceDrawChangeType();
        $changeTypeDesc = self::getChangeTypeDesc('', true);
        return array_filter($changeTypeDesc, function ($key) use ($UMChangeType) {
            return in_array($key, $UMChangeType);
        }, ARRAY_FILTER_USE_KEY);
    }


    /**
     * @notes 获取对话余额变动类型
     * @return int[]
     * @author 段誉
     * @date 2023/2/23 10:08
     */
    public static function getBalanceDrawChangeType() : array
    {
        return array_merge(self::DRAW_DEC, self::DRAW_INC);
    }


    /**
     * @notes 获取变动对象
     * @param $changeType
     * @return false
     * @author 段誉
     * @date 2023/2/23 10:10
     */
    public static function getChangeObject($changeType)
    {
        // 对话余额
        $um = self::getBalanceChangeType();
        if (in_array($changeType, $um)) {
            return self::UM;
        }

        // 可提现佣金
        $money = self::getUserMoneyChangeType();
        if (in_array($changeType, $money)) {
            return self::MONEY;
        }

        // 绘画余额
        $draw = self::getBalanceDrawChangeType();
        if (in_array($changeType, $draw)) {
            return self::DRAW;
        }

        return false;
    }
}
