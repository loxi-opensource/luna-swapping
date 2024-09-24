<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

return [
    'default' => 'redis',
    'connections' => [
        'sync' => [
            'type' => 'sync',
        ],
        'database' => [
            'type' => 'database',
            'queue' => 'default',
            'table' => 'jobs',
            'connection' => null,
            'retry_after' => 10, // 每次重试之间的延迟时间
        ],
        'redis' => [
            'type' => 'redis',
            'queue' => 'default',
            'host' => env('redis.host', 'like-redis'),
            'port' => env('redis.port', '6379'),
            'password' => env('redis.password', ''),
            'select' => env('redis.select', 0),
            'timeout' => 0,
            'persistent' => false,
        ],
    ],
    'failed' => [
        'type' => 'none',
        'table' => 'failed_jobs',
    ]
];
