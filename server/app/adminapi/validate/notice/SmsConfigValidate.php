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

namespace app\adminapi\validate\notice;

use app\common\validate\BaseValidate;

/**
 * 短信配置验证
 * Class SmsConfigValidate
 * @package app\adminapi\validate\notice
 */
class SmsConfigValidate extends BaseValidate
{
    protected $rule = [
        'type' => 'require',
        'sign' => 'requireIf:status,1',
        'secret_key' => 'requireIf:status,1',
        'status' => 'require|checkData',
    ];

    protected $message = [
        'type.require' => '请选择类型',
        'sign.requireIf' => '请输入签名',
        'secret_key.requireIf' => '请输入secret_key',
        'status.require' => '请选择状态',
    ];


    protected function sceneDetail()
    {
        return $this->only(['type']);
    }

    /**
     * @notes 校验数据
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2023/7/7 11:32 上午
     */
    public function checkData($value,$rule,$data)
    {
        if ($data['status'] == 1 && $data['type'] == 'tencent' && (!isset($data['app_id']) || $data['app_id'] == '')) {
            return '请输入app_id';
        }
        if ($data['status'] == 1 && $data['type'] == 'ali' && (!isset($data['app_key']) || $data['app_key'] == '')) {
            return '请输入app_key';
        }
        if ($data['status'] == 1 && $data['type'] == 'tencent' && (!isset($data['secret_id']) || $data['secret_id'] == '')) {
            return '请输入secret_id';
        }
        return true;
    }
}