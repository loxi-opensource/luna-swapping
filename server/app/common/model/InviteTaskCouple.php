<?php

namespace app\common\model;

use app\common\model\file\File;
use think\model\concern\SoftDelete;

class InviteTaskCouple extends BaseModel
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

    public function getUserDraftAttr($value)
    {
        return json_decode(trim($value, '"'), true);
    }

    public function invitingFile()
    {
        return $this->hasOne(File::class, 'id', 'inviting_file_id');
    }

    public function invitedFile()
    {
        return $this->hasOne(File::class, 'id', 'invited_file_id');
    }
}
