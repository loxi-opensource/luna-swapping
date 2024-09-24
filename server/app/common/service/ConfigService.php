<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace app\common\service;

use app\common\enum\CacheTagEnum;
use app\common\model\Config;
use app\common\utils\CacheUtils;
use think\facade\Cache;

class ConfigService
{
    /**
     * @notes 设置配置值
     * @param $type
     * @param $name
     * @param $value
     * @return mixed
     * @author 段誉
     * @date 2021/12/27 15:00
     */
    public static function set(string $type, string $name, $value)
    {
        $original = $value;
        if (is_array($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        $data = Config::where(['type' => $type, 'name' => $name])->findOrEmpty();

        if ($data->isEmpty()) {
            Config::create([
                'type' => $type,
                'name' => $name,
                'value' => $value,
            ]);
        } else {
            $data->value = $value;
            $data->save();
        }

        // 返回原始值
        return $original;
    }

    /**
     * @notes 获取配置值
     * @param $type
     * @param string $name
     * @param null $default_value
     * @return array|int|mixed|string
     * @author Tab
     * @date 2021/7/15 15:16
     */
    public static function get(string $type, string $name = '', $default_value = null)
    {
        // 直接读取项目配置文件
        if ($type == 'sms') {
            return config('project.sms.' . $name);
        }

        if (!empty($name)) {
            $value = Config::where(['type' => $type, 'name' => $name])->value('value');
            if (!is_null($value)) {
                $json = json_decode($value, true);
                $value = json_last_error() === JSON_ERROR_NONE ? $json : $value;
            }
            if ($value !== null) {
                return $value;
            }
            // 返回特殊值 0 '0'
            if ($value === 0 || $value === '0') {
                return $value;
            }
            // 返回默认值
            if ($default_value !== null) {
                return $default_value;
            }
            // 返回本地配置文件中的值
            return config('project.' . $type . '.' . $name);
        }

        // 取某个类型下的所有name的值
        $data = Config::where(['type' => $type])->column('value', 'name');
        foreach ($data as $k => $v) {
            $json = json_decode($v, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data[$k] = $json;
            }
        }
        if ($data) {
            return $data;
        }
    }

    // 从缓存读取
    static function getFromCache(string $type, string $name = '', $default_value = null)
    {
        $tag = CacheTagEnum::LUNA_CONFIG;
        $cacheKey = $tag . ':' . implode('-', [$type, $name]);
        return CacheUtils::rememberForever($cacheKey, function () use ($type, $name, $default_value) {
            return self::get($type, $name, $default_value);
        }, $tag);
    }

    // 刷新单个缓存
    static function refreshCache(string $type, string $name = '', $default_value = null)
    {
        $tag = CacheTagEnum::LUNA_CONFIG;
        $cacheKey = $tag . ':' . implode('-', [$type, $name]);
        CacheUtils::forget($cacheKey);
        self::getFromCache($type, $name, $default_value);
    }

    // 删除全部配置缓存
    static function clearCache()
    {
        $tag = CacheTagEnum::LUNA_CONFIG;
        Cache::tag($tag)->clear();
    }

    // 切换应用维护模式
    static function switchMaintanMode()
    {
        $config = (new Config())->where([
            'type' => 'maintenance_mode',
            'name' => 'open'
        ])->find();

        $config->value = intval(!$config->value);
        $config->save();
    }
}
