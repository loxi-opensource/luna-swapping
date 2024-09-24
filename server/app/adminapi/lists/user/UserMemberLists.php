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

namespace app\adminapi\lists\user;


use app\adminapi\lists\BaseAdminDataLists;
use app\common\model\user\UserMember;

class UserMemberLists extends BaseAdminDataLists
{
    /**
     * @notes 会员开通记录列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/6/21 10:43 上午
     */
    public function lists(): array
    {
        $lists = UserMember::field('id,user_id,operate_id,channel,package_name,member_end_time,is_perpetual,refund_status,create_time')
            ->append(['operate_desc','channel_desc','member_end_time_desc','refund_status_desc'])
            ->where(['user_id'=>$this->params['id']])
            ->order('id','desc')
            ->select()
            ->toArray();

        return $lists;
    }

    /**
     * @notes 会员开通记录数量
     * @return int
     * @author ljj
     * @date 2023/6/21 10:43 上午
     */
    public function count(): int
    {
        return UserMember::where(['user_id'=>$this->params['id']])->count();
    }
}