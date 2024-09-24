<?php

namespace app\common\enum;

class QueueNameConst
{
    const QueueDefault = "default";
    const QueueLunaDrawPollTask = "queue_luna_draw_poll_task";
    const QueueLunaDrawSubmitTask = "queue_luna_draw_submit_task";
    const QueuePaySuccess = "queue_pay_success";
    const QueuePrepaySuccess = "queue_prepay_success";
    const QueueCloudSendSms = "queue_send_sms";
    const QueueOnDrawingTaskUpdate = "queue_on_drawing_task_update";
}
