<?php

namespace app\common\utils;

use think\facade\Log;

class Utils
{
    // 判断用户请求版本号是否小于给定版本号
    static function isAppVersionBelow($appVersion)
    {
        $defaultVersion = '0.0.0';
        $userVersion = app()->request->header('app-version', $defaultVersion);
        if ($userVersion == 'undefined') {
            $userVersion = $defaultVersion;
        }
        return version_compare($userVersion, $appVersion) < 0;
    }
}
