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

namespace app\common\model\recharge;

use app\common\enum\PayEnum;
use app\common\enum\user\UserTerminalEnum;
use app\common\model\BaseModel;
use app\common\model\user\User;
use app\common\service\FileService;
use think\model\concern\SoftDelete;

/**
 * 充值订单模型
 * Class RechargeOrder
 * @package app\common\model
 */
class RechargeOrder extends BaseModel
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';


    /**
     * @notes 关联用户模型
     * @return \think\model\relation\HasOne
     * @author ljj
     * @date 2023/4/19 3:12 下午
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')
            ->field('id,sn,avatar,nickname,channel');
    }


    /**
     * @notes 支付方式
     * @param $value
     * @return string|string[]
     * @author 段誉
     * @date 2023/2/23 18:32
     */
    public function getPayWayTextAttr($value, $data)
    {
        return PayEnum::getPayDesc($data['pay_way']);
    }


    /**
     * @notes 支付状态
     * @param $value
     * @return string|string[]
     * @author 段誉
     * @date 2023/2/23 18:32
     */
    public function getPayStatusTextAttr($value, $data)
    {
        return PayEnum::getPayStatusDesc($data['pay_status']);
    }

    /**
     * @notes 支付时间
     * @param $value
     * @param $data
     * @return false|string
     * @author ljj
     * @date 2023/4/19 3:00 下午
     */
    public function getPayTimeTextAttr($value, $data)
    {
        return empty($data['pay_time']) ? '-' : date('Y-m-d H:i:s', $data['pay_time']);
    }

    /**
     * @notes 退款状态
     * @param $value
     * @param $data
     * @return string|string[]
     * @author ljj
     * @date 2023/4/19 3:04 下午
     */
    public function getRefundStatusTextAttr($value, $data)
    {
        return PayEnum::getRefundStatusDesc($data['refund_status']);
    }

    /**
     * @notes 订单来源
     * @param $value
     * @param $data
     * @return array|mixed|string|string[]
     * @author ljj
     * @date 2023/4/19 3:31 下午
     */
    public function getTerminalTextAttr($value, $data)
    {
        return UserTerminalEnum::getTermInalDesc($data['terminal']);
    }

    /**
     * @notes 订单类型
     * @param $value
     * @param $data
     * @return string
     * @author ljj
     * @date 2023/4/19 3:31 下午
     */
    public function getOrderTypeTextAttr($value, $data)
    {
        return '充值订单';
    }

    /**
     * @notes 用户头像
     * @param $value
     * @param $data
     * @return string
     * @author ljj
     * @date 2023/4/21 10:04 上午
     */
    public function getAvatarAttr($value, $data)
    {
        return empty($value) ? '' : FileService::getFileUrl($value);
    }

    /**
     * @notes 充值套餐名称
     * @param $value
     * @param $data
     * @return string
     * @author ljj
     * @date 2023/6/28 3:48 下午
     */
    public function getRechargePackageNameAttr($value, $data)
    {
        $recharge_package_info = empty($data['recharge_package_info']) ? [] : json_decode($data['recharge_package_info'], true);
        return $recharge_package_info['name'] ?? '-';
    }

    public function getRechargePackageInfoAttr($value)
    {
        $recharge_package_info = empty($value) ? [] : json_decode($value, true);
        return $recharge_package_info;
    }
}
