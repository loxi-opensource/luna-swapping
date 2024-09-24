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

namespace app\adminapi\lists\recharge;


use app\adminapi\lists\BaseAdminDataLists;
use app\api\logic\UserLogic;
use app\common\enum\PayEnum;
use app\common\lists\ListsExcelInterface;
use app\common\lists\ListsExtendInterface;
use app\common\model\recharge\RechargeOrder;

class RechargeOrderLists extends BaseAdminDataLists implements ListsExtendInterface, ListsExcelInterface
{
    /**
     * @notes 搜索条件
     * @return array
     * @author ljj
     * @date 2023/4/19 2:53 下午
     */
    public function where()
    {
//        $where[] = ['ro.pay_status','=',PayEnum::ISPAID];
        $where = [];
        if (isset($this->params['user_info']) && $this->params['user_info'] != '') {
            $where[] = ['u.id|u.sn|u.nickname|u.mobile', 'like', '%' . $this->params['user_info'] . '%'];
        }
        if (isset($this->params['terminal']) && $this->params['terminal'] != '') {
            $where[] = ['terminal', '=', $this->params['terminal']];
        }
        if (isset($this->params['refund_status']) && $this->params['refund_status'] != '') {
            $where[] = ['refund_status', '=', $this->params['refund_status']];
        }
        if (isset($this->params['pay_status']) && $this->params['pay_status'] != '') {
            $where[] = ['pay_status', '=', $this->params['pay_status']];
        }
        if (isset($this->params['is_free']) && $this->params['is_free'] != '') {
            $where[] = ['is_free', '=', $this->params['is_free']];
        }

        $this->params['start_time'] = $this->request->get('date_range')[0] ?? '';
        $this->params['end_time'] = $this->request->get('date_range')[1] ?? '';
        if ($this->params['end_time']) {
            $this->params['end_time'] .= ' 23:59:59';
        }

        if (isset($this->params['start_time']) && $this->params['start_time'] != '') {
            $where[] = ['ro.create_time', '>=', strtotime($this->params['start_time'])];
        }
        if (isset($this->params['end_time']) && $this->params['end_time'] != '') {
            $where[] = ['ro.create_time', '<=', strtotime($this->params['end_time'])];
        }

        return $where;
    }

    /**
     * @notes 充值订单列表
     * @return array
     * @author ljj
     * @date 2023/4/19 3:04 下午
     */
    public function lists(): array
    {
        $taskTable = \think\facade\Db::name('luna_drawing_task')
            ->field('order_id, MAX(up_task_id) AS up_task_id')
            ->group('order_id')
            ->buildSql();

        $lists = RechargeOrder::alias('ro')
            ->leftjoin('user u', 'u.id = ro.user_id')
            ->leftjoin([$taskTable => 'T'], 'ro.id = T.order_id')
            ->field('ro.id,ro.is_free,ro.pay_way,ro.user_id,ro.sn,u.avatar,u.nickname,u.sn as user_sn,ro.number,ro.order_amount,ro.pay_status,ro.pay_time,ro.refund_status,ro.draw_number,ro.recharge_package_info,ro.draw_number,T.up_task_id,u.login_ip,u.login_ipinfo')
            ->append(['pay_status_text', 'pay_time_text', 'refund_status_text', 'recharge_package_name'])
            ->where($this->where())
            ->order('ro.id', 'desc')
            ->limit($this->limitOffset, $this->limitLength)
            ->select()
            ->toArray();

        foreach ($lists as &$r) {
            $r['free_type'] = '';
            $r['is_free_text'] = '';
            if ($r['is_free']) {
                $r['free_type'] = UserLogic::isinternalTestingMember($r['user_id']) ? '内部人员免费' : '新用户免费';
                $r['is_free_text'] = '是';
            }
            $r['pay_type_desc'] = '';
        }

        return $lists;
    }

    /**
     * @notes 充值订单数量
     * @return int
     * @author ljj
     * @date 2023/4/19 3:05 下午
     */
    public function count(): int
    {
        return RechargeOrder::alias('ro')
            ->leftjoin('user u', 'u.id = ro.user_id')
            ->where($this->where())
            ->count();
    }

    /**
     * @notes 统计数据
     * @return array
     * @author ljj
     * @date 2023/4/19 3:37 下午
     */
    public function extend()
    {
        $total_amount = RechargeOrder::where(['pay_status' => PayEnum::ISPAID])->sum('order_amount');
        $refund_total_amount = RechargeOrder::where(['pay_status' => PayEnum::ISPAID, 'refund_status' => PayEnum::REFUND_SUCCESS])->sum('order_amount');
        return [
            'order_num' => RechargeOrder::where(['pay_status' => PayEnum::ISPAID])->count(),
            'total_amount' => $total_amount,
            'refund_order_num' => RechargeOrder::where(['pay_status' => PayEnum::ISPAID, 'refund_status' => PayEnum::REFUND_SUCCESS])->count(),
            'refund_total_amount' => $refund_total_amount,
            'net_income' => round($total_amount - $refund_total_amount, 2),
        ];
    }


    public function setFileName(): string
    {
        return '充值订单列表';
    }


    public function setExcelFields(): array
    {
        return [
            'sn' => '订单号',
            'user_id' => '用户ID',
            'is_free_text' => '是否免费',
            'free_type' => '免费方式',
            'pay_type_desc' => '支付方式',
            'order_amount' => '支付金额',
            'pay_status' => '支付状态', // todo 退款状态
            'pay_time_text' => '支付时间',
            'draw_number' => '产品张数',
            'nickname' => '用户昵称',
            'up_task_id' => '上游任务ID',
            'login_ip' => '最近登录IP',
            'login_ipinfo' => 'IP信息'
        ];
    }


}
