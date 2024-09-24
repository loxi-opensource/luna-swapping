<?php

namespace app\job;

use app\api\logic\LunaDrawLogic;
use app\common\model\luna\SwapTask;
use app\common\utils\LogUtils;
use think\queue\Job;

class LunaDrawingPollingJob
{
    public function fire(Job $job, $data)
    {
        echo "recv msg: ", json_encode($data), PHP_EOL;

        // 调用上游服务获取结果
        $result = $this->callUpstreamService($data['user_id'], $data['up_task_id']);

        echo $result, PHP_EOL;

        // 根据返回结果进行处理
        if ($result === 'completed') {
            // 上游业务完成，标记任务完成并删除
            $job->delete();
        } elseif ($result === 'failed') {
            // 上游业务失败，标记任务完成并删除
            $job->delete();
        } else {
            // 继续轮询，重新投递消息到队列
            $job->release(5);
        }
    }

    private function callUpstreamService($user_id, $up_task_id)
    {
        try {
            $res = LunaDrawLogic::pollTaskStatusV3($user_id, $up_task_id);
        } catch (\Exception $e) {
            // 上游响应异常，但可以忽略。比如访问频率受限制
            echo "上游响应异常: ", $e->getMessage(), PHP_EOL;
            return "continue";
        }
        if (!$res) {
            return "failed";
        }

        if ($res['status'] == SwapTask::STATUS_PROCESSING) {
            return "continue";
        } else if ($res['status'] == SwapTask::STATUS_SUCCESS || $res['status'] == SwapTask::STATUS_FAIL) {
            return 'completed';
        } else {
            return "failed";
        }
    }

    public function failed($data, \Exception $e)
    {
        LogUtils::log('failed job', [
            'data' => $data,
            'errmsg' => $e->getMessage()
        ]);
        echo 'failed job ', json_encode($data) . " " . $e->getMessage(), PHP_EOL;
    }
}
