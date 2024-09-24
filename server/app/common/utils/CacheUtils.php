<?php

namespace app\common\utils;

use Psr\Cache\CacheException;
use think\facade\Cache;

class CacheUtils
{
    static function remember($key, callable $callback, $minutes, $tag = null)
    {
        // 尝试从缓存中获取数据
        $value = Cache::get($key);

        // 如果缓存中存在数据，则直接返回
        if (!is_null($value)) {
//            LogUtils::log('cache hit '.$key);
            return $value;
        }
        LogUtils::log('cache miss '.$key);

        // 如果缓存中不存在数据，则执行回调函数获取数据
        $value = call_user_func($callback);
        // 将获取到的数据写入缓存，并设置过期时间（以分钟为单位）
        if ($tag) {
            Cache::tag($tag)->set($key, $value, $minutes * 60);
        } else {
            Cache::set($key, $value, $minutes * 60);
        }

        return $value;
    }

    static function rememberForever($key, callable $callback, $tag = null)
    {
        $value = Cache::get($key);
        if (!is_null($value)) {
//            LogUtils::log('cache hit '.$key);
            return $value;
        }

        LogUtils::log('cache miss '.$key);
        $value = call_user_func($callback);
        if ($tag) {
            Cache::tag($tag)->set($key, $value);
        } else {
            Cache::set($key, $value);
        }

        return $value;
    }

    static function forget($key)
    {
        Cache::delete($key);
    }

    static function flush($tag)
    {
        Cache::tag($tag)->clear();
    }
}
