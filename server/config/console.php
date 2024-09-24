<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        // 定时任务
        'crontab' => 'app\common\command\Crontab',
        // 退款查询
        'query_refund' => 'app\common\command\QueryRefund',
        // 内容审核
        'content_censor' => 'app\common\command\ContentCensor',
        // 修改超级管理员密码
        'password' => 'app\common\command\Password',
        // 测试脚本
        'test' => 'app\command\Test',
        // 更新LunaAI写真官方模板
        'luna_upgrade_official_template' => 'app\command\LunaUpgradeOfficialTemplate',
    ],
];
