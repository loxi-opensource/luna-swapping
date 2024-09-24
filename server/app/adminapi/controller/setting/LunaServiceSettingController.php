<?php

namespace app\adminapi\controller\setting;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\setting\LunaServiceSettingLogic;

class LunaServiceSettingController extends BaseAdminController
{
    public function getConfig()
    {
        $result = (new LunaServiceSettingLogic())->getConfig();
        return $this->success('', $result);
    }

    public function setConfig()
    {
        $params = $this->request->post();
        (new LunaServiceSettingLogic())->setConfig($params);
        return $this->success('设置成功', [], 1, 1);
    }

}
