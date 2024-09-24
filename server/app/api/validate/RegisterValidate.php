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
namespace app\api\validate;


use app\common\enum\notice\NoticeEnum;
use app\common\model\user\User;
use app\common\service\ConfigService;
use app\common\service\sms\SmsDriver;
use app\common\validate\BaseValidate;

/**
 * 注册验证器
 * Class RegisterValidate
 * @package app\api\validate
 */
class RegisterValidate extends BaseValidate
{

    protected $regex = [
        'register' => '^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$',
        'password' => '/^(?![0-9]+$)(?![a-z]+$)(?![A-Z]+$)(?!([^(0-9a-zA-Z)]|[\(\)])+$)([^(0-9a-zA-Z)]|[\(\)]|[a-z]|[A-Z]|[0-9]){6,20}$/'
    ];

    protected $rule = [
        'channel' => 'require',
        'account' => 'require|mobile|unique:' . User::class .',mobile|checkCode',
        'password' => 'require|length:6,20|regex:password',
        'password_confirm' => 'require|confirm',
    ];

    protected $message = [
        'channel.require' => '注册来源参数缺失',
        'account.require' => '请输入邮箱',
        'account.mobile' => '手机号码错误',
        'account.unique' => '手机号码已存在',
        'password.require' => '请输入密码',
        'password.length' => '密码须在6-20位之间',
        'password.regex' => '密码须为数字,字母或符号组合',
        'password_confirm.require' => '请确认密码',
        'password_confirm.confirm' => '两次输入的密码不一致',
    ];


    /**
     * @notes 校验验证码
     * @param $code
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2023/5/22 2:58 下午
     */
    public function checkCode($code, $rule, $data)
    {
        $sms_verify = ConfigService::get('login', 'sms_verify', config('project.login.sms_verify'));
        if ($sms_verify == 1) {
            if (!isset($data['code']) || $data['code'] == '') {
                return '请输入验证码';
            }

            $smsDriver = new SmsDriver();
            $result = $smsDriver->verify($data['account'], $data['code'], NoticeEnum::REGISTER_CAPTCHA);
            if (!$result) {
                return 'Wrong verification code';
            }
        }

        return true;
    }
}
