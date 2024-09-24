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

namespace app\common\model\recharge;


use app\common\enum\user\UserTerminalEnum;
use app\common\model\BaseModel;
use think\model\concern\SoftDelete;

class RechargePackage extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    const UserFreeTryTimesLimitPerDay = 3;

    const AllowFreeTryTerminals = [
        UserTerminalEnum::DOUYIN_MMP,
        UserTerminalEnum::WECHAT_MMP,
    ];

    /**
     * @notes 额外赠送状态
     * @param $value
     * @param $data
     * @return string
     * @author ljj
     * @date 2023/6/28 11:15 上午
     */
    public function getIsGiveDescAttr($value,$data)
    {
        return $data['is_give'] == 1 ? '开启' : '关闭';
    }

    static function isTerminalAllowFreeTry($terminalID)
    {
        return in_array($terminalID, self::AllowFreeTryTerminals);
    }
}
