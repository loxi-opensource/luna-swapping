<?php

namespace app\adminapi\logic\setting;

use app\common\service\ConfigService;

class ImageCheckSettingLogic
{
    public function getConfig(): array
    {
        return [
            'audit_open' => ConfigService::get('image_check', 'audit_open', 0),
            'access_key' => ConfigService::get('image_check', 'access_key', ''),
            'secret_key' => ConfigService::get('image_check', 'secret_key', ''),
            'endpoint' => ConfigService::get('image_check', 'endpoint', ''),
            'region_id' => ConfigService::get('image_check', 'region_id', ''),
        ];
    }

    public function setConfig(array $params): bool
    {
        ConfigService::set('image_check', 'audit_open', $params['audit_open']);
        ConfigService::set('image_check', 'access_key', $params['access_key']);
        ConfigService::set('image_check', 'secret_key', $params['secret_key']);
        ConfigService::set('image_check', 'endpoint', $params['endpoint']);
        ConfigService::set('image_check', 'region_id', $params['region_id']);
        return true;
    }

}
