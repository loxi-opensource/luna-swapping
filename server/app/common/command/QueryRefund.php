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

namespace app\common\command;

use app\common\enum\PayEnum;
use app\common\enum\RefundEnum;
use app\common\model\member\MemberOrder;
use app\common\model\recharge\RechargeOrder;
use app\common\model\refund\RefundLog;
use app\common\model\refund\RefundRecord;
use app\common\service\pay\AliPayService;
use app\common\service\pay\WeChatPayService;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Log;


class QueryRefund extends Command
{
    protected function configure()
    {
        $this->setName('query_refund')
            ->setDescription('订单退款状态处理');
    }


    protected function execute(Input $input, Output $output)
    {
        try {
            // 查找退款中的退款记录(微信，支付宝支付)
            $refundRecords = (new RefundLog())->alias('l')
                ->join('refund_record r', 'r.id = l.record_id')
                ->field([
                    'l.id' => 'log_id', 'l.sn' => 'log_sn',
                    'r.id' => 'record_id', 'r.order_id', 'r.sn' => 'record_sn', 'r.order_type', 'r.order_sn'
                ])
                ->where(['l.refund_status' => RefundEnum::REFUND_ING])
                ->select()->toArray();

            if (empty($refundRecords)) {
                return false;
            }

            foreach ($refundRecords as $record) {
                $order = [];
                if ($record['order_type'] == 'recharge') {
                    $order = RechargeOrder::whereIn('id', $record['order_id'])->findOrEmpty()->toArray();
                }
                if ($record['order_type'] == 'member') {
                    $order = MemberOrder::whereIn('id', $record['order_id'])->findOrEmpty()->toArray();
                }
                if (empty($order)) {
                    continue;
                }

                if (!in_array($order['pay_way'], [PayEnum::WECHAT_PAY, PayEnum::ALI_PAY])) {
                    continue;
                }

                $this->checkReFundStatus([
                    'record_id' => $record['record_id'],
                    'log_id' => $record['log_id'],
                    'log_sn' => $record['log_sn'],
                    'pay_way' => $order['pay_way'],
                    'terminal' => $order['terminal'],
                    'order_id' => $record['order_id'],
                    'order_type' => $record['order_type'],
                    'order_sn' => $record['order_sn'],
                ]);
            }

            return true;
        } catch (\Exception $e) {
            Log::write('订单退款状态查询失败,失败原因:' . $e->getMessage());
            return false;
        }
    }


    /**
     * @notes 校验退款状态
     * @param $refundData
     * @return bool
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @author 段誉
     * @date 2023/3/1 15:54
     */
    public function checkReFundStatus($refundData)
    {
        $result = null;
        switch ($refundData['pay_way']) {
            case PayEnum::WECHAT_PAY:
                $result = self::checkWechatRefund($refundData['terminal'], $refundData['log_sn']);
                break;
            case PayEnum::ALI_PAY:
                $result = self::checkAliRefund($refundData['order_sn'], $refundData['log_sn']);
                break;
        }

        if (is_null($result)) {
            return false;
        }

        if (!empty($result['status']) && $result['status'] == 'SUCCESS') {
            $this->updateRefundSuccess($refundData['log_id'], $refundData['record_id']);

            // 更新订单信息
            if ($refundData['order_type'] == 'recharge') {
                RechargeOrder::update([
                    'id' => $refundData['order_id'],
                    'refund_transaction_id' => $result['refund_id'] ?? '',
                ]);
            }
            if ($refundData['order_type'] == 'member') {
                MemberOrder::update([
                    'id' => $refundData['order_id'],
                    'refund_transaction_id' => $result['refund_id'] ?? '',
                ]);
            }

        } else {
            $this->updateRefundMsg($refundData['log_id'], $result);
        }
        return true;
    }


    /**
     * @notes 查询微信支付退款状态
     * @param $orderTerminal
     * @param $refundLogSn
     * @return bool|string|null
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @author 段誉
     * @date 2023/3/1 15:47
     */
    public function checkWechatRefund($orderTerminal, $refundLogSn)
    {
        // 根据商户退款单号查询退款
        $result = (new WeChatPayService($orderTerminal))->queryRefund($refundLogSn);

        if (!empty($result['code']) || !empty($result['message'])) {
            return '微信:' . $result['code'] . '-' . $result['message'];
        }

        return $result;
    }


    /**
     * @notes 支付宝退款查询
     * @param $order_sn
     * @param $refundLogSn
     * @return array|string
     * @author ljj
     * @date 2023/5/16 4:31 下午
     */
    public function checkAliRefund($order_sn,$refundLogSn)
    {
        $result = (new AliPayService())->queryRefund($order_sn, $refundLogSn);
        $result = (array)$result;
        if ($result['code'] != '10000' || $result['msg'] != 'Success') {
            return '支付宝:' . $result['code'] . '-' . $result['msg'];
        }
        if (isset($result['refund_status']) && $result['refund_status'] == 'REFUND_SUCCESS') {
            $result['status'] = 'SUCCESS';
            $result['refund_id'] = $result['tradeNo'] ?? '';
        }

        return $result;
    }


    /**
     * @notes 更新记录为成功
     * @param $logId
     * @param $recordId
     * @author 段誉
     * @date 2023/3/1 15:38
     */
    public function updateRefundSuccess($logId, $recordId)
    {
        // 更新日志
        RefundLog::update([
            'id' => $logId,
            'refund_status' => RefundEnum::REFUND_SUCCESS,
        ]);
        // 更新记录
        RefundRecord::update([
            'id' => $recordId,
            'refund_status' => RefundEnum::REFUND_SUCCESS,
        ]);
    }


    /**
     * @notes 更新退款信息
     * @param $logId
     * @param $msg
     * @author 段誉
     * @date 2023/3/1 15:47
     */
    public function updateRefundMsg($logId, $msg)
    {
        // 更新日志
        RefundLog::update([
            'id' => $logId,
            'refund_msg' => json_encode($msg),
        ]);
    }

}
