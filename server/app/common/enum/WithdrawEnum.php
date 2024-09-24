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

namespace app\common\enum;


class WithdrawEnum
{
    //提现方式
    const TYPE_ALI = 1;//支付宝
    const TYPE_WECHAT = 2;//微信零钱
    const TYPE_WECHAT_QRCODE = 3;//微信收款码
    const TYPE_ALI_QRCODE = 4;//支付宝收款码

    //提现状态
    const STATUS_WAIT = 1;//待审核
    const STATUS_ING = 2;//提现中
    const STATUS_SUCCESS = 3;//提现成功
    const STATUS_FAIL = 4;//提现失败

    //审核状态
    const VERIFY_STATUS_WAIT = 1;//待审核
    const VERIFY_STATUS_SUCCESS = 2;//审核通过
    const VERIFY_STATUS_FAIL = 3;//审核拒绝


    //微信零钱提现方式
    const ENTERPRISE = 1;//企业付款到零钱
    const MERCHANT = 2;//商家转账到零钱


    /**
     * @notes 提现方式
     * @param bool $value
     * @return string|string[]
     * @author ljj
     * @date 2023/5/24 2:26 下午
     */
    public static function getTypeDesc($value = true)
    {
        $data = [
            self::TYPE_ALI => '支付宝',
            self::TYPE_WECHAT => '微信零钱',
            self::TYPE_WECHAT_QRCODE => '微信收款码',
            self::TYPE_ALI_QRCODE => '支付宝收款码',
        ];
        if ($value === true) {
            return $data;
        }
        return $data[$value] ?? '';
    }

    /**
     * @notes 提现状态
     * @param bool $value
     * @return string|string[]
     * @author ljj
     * @date 2023/5/24 2:26 下午
     */
    public static function getStatusDesc($value = true)
    {
        $data = [
            self::STATUS_WAIT => '待审核',
            self::STATUS_ING => '提现中',
            self::STATUS_SUCCESS => '提现成功',
            self::STATUS_FAIL => '提现失败',
        ];
        if ($value === true) {
            return $data;
        }
        return $data[$value] ?? '';
    }

    /**
     * @notes 审核状态
     * @param bool $value
     * @return string|string[]
     * @author ljj
     * @date 2023/5/24 2:56 下午
     */
    public static function getVerifyStatusDesc($value = true)
    {
        $data = [
            self::VERIFY_STATUS_WAIT => '待审核',
            self::VERIFY_STATUS_SUCCESS => '审核通过',
            self::VERIFY_STATUS_FAIL => '审核拒绝',
        ];
        if ($value === true) {
            return $data;
        }
        return $data[$value];
    }
}