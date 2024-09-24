<?php

namespace app\adminapi\logic\setting;

use app\common\service\ConfigService;

class LunaServiceSettingLogic
{
    public function getConfig(): array
    {
        return [
            'secret' => ConfigService::get('luna_service', 'secret', ''),
            'secret_key' => ConfigService::get('luna_service', 'secret_key', ''),
        ];
    }

    public function setConfig(array $params): bool
    {
        ConfigService::set('luna_service', 'secret', $params['secret']);
        ConfigService::set('luna_service', 'secret_key', $params['secret_key']);
        return true;
    }

}
