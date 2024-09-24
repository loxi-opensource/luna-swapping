<?php

namespace app\job;

use app\api\logic\LunaDrawLogic;
use app\api\logic\RechargeLogic;
use app\common\enum\QueueNameConst;
use app\common\model\luna\LunaDrawingTask;
use app\common\model\recharge\RechargeOrder;
use app\common\service\luna\LunaDrawService;
use app\common\utils\LogUtils;
use think\Exception;
use think\helper\Arr;
use think\helper\Str;
use think\queue\Job;

class LunaDrawingOnPaySuccessJob
{
    public function fire(Job $job, $data)
    {
        echo "recv msg: ", json_encode($data), PHP_EOL;

        $taskExist = (new LunaDrawingTask())->where([
            'order_id' => $data['orderID'],
            'is_retry' => 0,
        ])->field('id,order_id,is_retry,status,create_time')->find();
        if ($taskExist) {
            $msg = '订单任务已存在，无需处理';
            echo 'ignore job ', json_encode($data) . " " . $msg, PHP_EOL;
            $job->delete();
            return;
        }

        $payedOrder = (new RechargeOrder())->where([
            'id' => $data['orderID'],
            'pay_status' => 1,
        ])->find();
        if (!$payedOrder) {
            $msg = '订单还没有支付成功';
            echo 'invalid job ', json_encode($data) . " " . $msg, PHP_EOL;
            $job->delete();
            return;
        }

        // 调用上游服务获取结果
        $draft = json_decode($payedOrder->user_draft, true);
        $result = LunaDrawLogic::submitTask($payedOrder->user_id, $payedOrder->draw_number,
            $draft, null, $payedOrder);
        $state = $result ? 'completed' : 'failed';
        if (!$result) {
            $errMsg = LunaDrawLogic::getError();
            if (Str::contains($errMsg, '重试')) {
                $state = 'continue';
            }
        }

        echo $state, PHP_EOL;

        // 根据返回结果进行处理
        if ($state === 'completed') {
            // 上游业务完成，标记任务完成并删除
            $job->delete();
        } elseif ($state === 'failed') {
            // 上游业务失败，标记任务完成并删除
            $job->delete();
        } else {
            // 继续轮询，重新投递消息到队列
            $job->release(5);
        }
    }

    public function failed($data, \Exception $e)
    {
        echo 'failed job ', json_encode($data) . " " . $e->getMessage(), PHP_EOL;
    }
}
