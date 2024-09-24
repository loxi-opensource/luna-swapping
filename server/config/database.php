<?php

return [
    // 默认使用的数据库连接配置
    'default'         => env('database.driver', 'mysql'),

    // 自定义时间查询规则
    'time_query_rule' => [],

    // 自动写入时间戳字段
    'auto_timestamp'  => true,

    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',

    // 时间字段配置
    'datetime_field'  => '',

    // 数据库连接配置信息
    'connections'     => [
        'mysql' => [
            // 数据库类型
            'type'            => env('database.type', 'mysql'),
            // 服务器地址
            'hostname'        => getenv('CDK_SPRING_DATASOURCE_HOST') ?: env('database.hostname', 'likeshop-mysql'),
            // 数据库名
            'database'        => getenv('CDK_SPRING_DATASOURCE_NAME') ?: env('database.database', 'localhost_likeadmin'),
            // 用户名
            'username'        => getenv('CDK_SPRING_DATASOURCE_USERNAME') ?: env('database.username', 'root'),
            // 密码
            'password'        => getenv('CDK_SPRING_DATASOURCE_PASSWORD') ?: env('database.password', 'root'),
            // 端口
            'hostport'        => getenv('CDK_SPRING_DATASOURCE_PORT') ?: env('database.hostport', '3306'),
            // 数据库连接参数
            'params'          => [],
            // 数据库编码默认采用utf8
            'charset'         => env('database.charset', 'utf8mb4'),
            // 数据库表前缀
            'prefix'          => env('database.prefix', 'la_'),
            // 数据库部署方式
            'deploy'          => 0,
            // 数据库读写是否分离
            'rw_separate'     => false,
            // 主服务器数量
            'master_num'      => 1,
            // 指定从服务器序号
            'slave_no'        => '',
            // 是否严格检查字段是否存在
            'fields_strict'   => true,
            // 是否需要断线重连
            'break_reconnect' => true,
            // 监听SQL
            'trigger_sql'     => env('database.debug', false),
            // 开启字段缓存
            'fields_cache'    => false,
        ],

        // 更多的数据库配置信息
    ],
];
