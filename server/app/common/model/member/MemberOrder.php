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

namespace app\common\model\member;


use app\common\enum\PayEnum;
use app\common\enum\user\UserTerminalEnum;
use app\common\model\BaseModel;
use app\common\model\user\User;
use app\common\service\FileService;
use think\model\concern\SoftDelete;

class MemberOrder extends BaseModel
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
        return $this->hasOne(User::class,'id','user_id')
            ->field('id,sn,avatar,nickname');
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
        return empty($data['pay_time']) ? '-' : date('Y-m-d H:i:s',$data['pay_time']);
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
        return '会员订单';
    }


    /**
     * @notes 会员套餐名称
     * @param $value
     * @param $data
     * @return string
     * @author ljj
     * @date 2023/4/21 9:52 上午
     */
    public function getMemberPackageAttr($value,$data)
    {
        $member_package = json_decode($data['member_package_info'],true);
        if (isset($member_package['is_perpetual']) && $member_package['is_perpetual'] == 1) {
            return $member_package['name'].'(永久)';
        } else {
            return $member_package['name'].'('.$member_package['duration'].'个月)';
        }
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
}