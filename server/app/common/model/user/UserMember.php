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

namespace app\common\model\user;


use app\common\enum\MemberPackageEnum;
use app\common\enum\PayEnum;
use app\common\model\auth\Admin;
use app\common\model\BaseModel;
use think\model\concern\SoftDelete;

class UserMember extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';


    /**
     * @notes 退款状态
     * @param $value
     * @param $data
     * @return string|string[]
     * @author ljj
     * @date 2023/4/19 3:04 下午
     */
    public function getRefundStatusDescAttr($value, $data)
    {
        return PayEnum::getRefundStatusDesc($data['refund_status']);
    }

    /**
     * @notes 操作人
     * @param $value
     * @param $data
     * @return mixed|string
     * @author ljj
     * @date 2023/6/21 10:03 上午
     */
    public function getOperateDescAttr($value, $data)
    {
        if ($data['channel'] == MemberPackageEnum::CHANNEL_ADMIN) {
            return Admin::where(['id'=>$data['operate_id']])->value('name') ?? '未知';
        } else {
            return '-';
        }
    }

    /**
     * @notes 购买来源
     * @param $value
     * @param $data
     * @return string|string[]
     * @author ljj
     * @date 2023/6/21 10:24 上午
     */
    public function getChannelDescAttr($value, $data)
    {
        return MemberPackageEnum::getChannelDesc($data['channel']);
    }

    /**
     * @notes 到期时间
     * @param $value
     * @param $data
     * @return false|string
     * @author ljj
     * @date 2023/6/21 10:40 上午
     */
    public function getMemberEndTimeDescAttr($value, $data)
    {
        if ($data['is_perpetual']) {
            $result = '永久';
        } else {
            $result = empty($data['member_end_time']) ? '未开通' : date('Y-m-d H:i:s',$data['member_end_time']);
        }

        return $result;
    }
}
