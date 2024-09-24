<?php

namespace app\index\controller;

use app\api\logic\UserLogic;
use app\BaseController;
use app\common\enum\CacheTagEnum;
use app\common\model\Config;
use app\common\service\ConfigService;
use app\common\service\JsonService;
use think\facade\Cache;
use think\facade\Log;

class IndexController extends BaseController
{
    public function index($name = 'hello luna')
    {
        try {
            $dbTestResult = (new Config())->limit(1)->field('id')->select();
        } catch (\Exception $exception) {
            throw new \Exception('DB init failed');
        }

        try {
            $cacheTestResult = Cache::set('hello', 'hello cache');
        } catch (\Exception $exception) {
            throw new \Exception('Cache init failed');
        }

        try {
            $redisQueue = (new \think\Queue(app()))->connection('redis');
        } catch (\Exception $exception) {
            throw new \Exception('Redis-Queue init failed');
        }

        return JsonService::success($name, [
            'db' => boolval($dbTestResult),
            'cache' => boolval($cacheTestResult),
            'redis-queue' => boolval($redisQueue),
        ]);
    }
}
