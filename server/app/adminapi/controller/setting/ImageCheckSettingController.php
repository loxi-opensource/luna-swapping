<?php

namespace app\adminapi\controller\setting;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\setting\ImageCheckSettingLogic;

class ImageCheckSettingController extends BaseAdminController
{
    public function getConfig()
    {
        $result = (new ImageCheckSettingLogic())->getConfig();
        return $this->success('', $result);
    }

    public function setConfig()
    {
        $params = $this->request->post();
        (new ImageCheckSettingLogic())->setConfig($params);
        return $this->success('设置成功', [], 1, 1);
    }

}
