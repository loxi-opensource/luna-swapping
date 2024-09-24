<?php

namespace app\job;

use app\common\enum\PayEnum;
use app\common\enum\user\UserTerminalEnum;
use app\common\model\InviteTaskCouple;
use app\common\model\LunaDrawingTask;
use app\common\model\recharge\RechargeOrder;
use app\common\service\pay\DouyinPayService;
use think\queue\Job;

class OnDrawingTaskUpdateJob
{
    public function fire(Job $job, $data)
    {
        echo "recv msg: ", json_encode($data), PHP_EOL;

        try {
            if ($data['is_share'] == 1) {
                // 同步任务状态到父级分享任务
                $task = LunaDrawingTask::find($data['task_id']);
                if (!$task) {
                    throw new \Exception('OnDrawingTaskUpdateJob - task not found');
                }
                $invitedRecord = InviteTaskCouple::find($data['share_task_id']);
                $invitingRecord = InviteTaskCouple::find($invitedRecord->pid);
                if (!$invitedRecord || !$invitingRecord) {
                    throw new \Exception('OnDrawingTaskUpdateJob - invite record not found');
                }

                $toCopy = $task->toArray();
                $toCopy['user_id'] = $invitedRecord->inviting_user_id;
                unset($toCopy['create_time']);
                unset($toCopy['update_time']);
                unset($toCopy['id']);

                $parentTask = LunaDrawingTask::where([
                    'is_share' => 1,
                    'share_task_id' => $task->share_task_id,
                    'user_id' => $invitedRecord->inviting_user_id,
                ])->find();
                if (!$parentTask) {
                    echo 'create new', PHP_EOL;
                    LunaDrawingTask::create($toCopy);
                } else {
                    echo 'update', PHP_EOL;
                    $parentTask->save($toCopy);
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage(), PHP_EOL;
        }

        $job->delete();
    }

    public function failed($data, \Exception $e)
    {
        echo 'failed job ', json_encode($data) . " " . $e->getMessage(), PHP_EOL;
    }
}
