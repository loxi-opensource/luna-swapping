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

namespace app\adminapi\lists;


use app\common\model\Feedback;

class FeedbackLists extends BaseAdminDataLists
{
    /**
     * @notes 搜索条件
     * @return array
     * @author ljj
     * @date 2023/4/23 10:14 上午
     */
    public function where()
    {
        $where = [];
        if (isset($this->params['type']) && $this->params['type'] != '') {
            $where[] = ['f.type','=',$this->params['type']];
        }
        if (isset($this->params['start_time']) && $this->params['start_time'] != '') {
            $where[] = ['f.create_time','>=',strtotime($this->params['start_time'])];
        }
        if (isset($this->params['end_time']) && $this->params['end_time'] != '') {
            $where[] = ['f.create_time','<=',strtotime($this->params['end_time'])];
        }

        return $where;
    }

    /**
     * @notes 意见反馈列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/4/23 10:17 上午
     */
    public function lists(): array
    {
        $lists = Feedback::alias('f')
            ->join('user u', 'f.user_id = u.id')
            ->field('f.id,f.type,f.content,f.mobile,f.create_time,u.avatar,u.nickname')
            ->append(['type_desc'])
            ->order(['f.id'=>'desc'])
            ->where($this->where())
            ->limit($this->limitOffset, $this->limitLength)
            ->select()
            ->toArray();

        return $lists;
    }

    /**
     * @notes 意见反馈数量
     * @return int
     * @author ljj
     * @date 2023/4/23 10:17 上午
     */
    public function count(): int
    {
        return Feedback::alias('f')
            ->join('user u', 'f.user_id = u.id')
            ->where($this->where())
            ->count();
    }
}