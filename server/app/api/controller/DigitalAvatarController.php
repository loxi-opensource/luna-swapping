<?php

namespace app\api\controller;

use app\common\model\luna\DigitalAvatar;

class DigitalAvatarController extends BaseApiController
{
    public function list()
    {
        $list = DigitalAvatar::where('user_id', $this->userId)
            ->order('id desc')
            ->limit(9)
            ->select();
        return $this->success('Success', compact('list'));
    }

    public function remove()
    {
        DigitalAvatar::where('user_id', $this->userId)
            ->where('id', $this->request->post('id'))
            ->delete();
        return $this->success('Success');
    }

}
