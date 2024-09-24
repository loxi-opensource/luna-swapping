<?php

namespace app\common\utils;

use think\facade\Log;

class LogUtils
{
    static function record(\Throwable $e, $title, $data)
    {
        Log::record(implode(' - ', [
            $title,
            $e->getMessage(),
            json_encode(['data' => $data], JSON_UNESCAPED_UNICODE),
            $e->getFile() . ":" . $e->getLine(),
//            $e->getTraceAsString(),
        ]));
    }

    static function log($title, $data = null)
    {
        $logData = [$title];
        if ($data) {
            $logData[] = json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
        }
        Log::record(implode(' - ', $logData));
    }
}
