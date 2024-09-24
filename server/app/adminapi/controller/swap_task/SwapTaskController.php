<?php

namespace app\adminapi\controller\swap_task;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\swap_task\SwapTaskLists;

class SwapTaskController extends BaseAdminController
{
    public function lists()
    {
        return $this->dataLists(new SwapTaskLists());
    }
}
