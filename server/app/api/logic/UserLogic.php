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

namespace app\api\logic;


use app\common\{enum\notice\NoticeEnum,
    enum\PayEnum,
    enum\user\UserTerminalEnum,
    enum\YesNoEnum,
    logic\BaseLogic,
    model\user\User,
    model\user\UserAuth,
    model\user\UserAuthDy,
    model\user\UserMember,
    service\ConfigService,
    service\sms\SmsDriver,
    service\wechat\WeChatMnpService,
    utils\CacheUtils
};
use http\Exception\InvalidArgumentException;
use think\facade\Cache;
use think\facade\Config;
use think\facade\Log;

/**
 * 会员逻辑层
 * Class UserLogic
 * @package app\shopapi\logic
 */
class UserLogic extends BaseLogic
{

    /**
     * @notes 个人中心
     * @param array $userInfo
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/16 18:04
     */
    public static function center(array $userInfo): array
    {
        // todo 优化 统一逻辑获取会员资格
        $user = User::where(['id' => $userInfo['user_id']])
            ->field('id,sn,sex,account,nickname,real_name,avatar,mobile,create_time,is_new_user,balance,password,member_end_time,member_perpetual,balance_draw')
            ->findOrEmpty();

        if (in_array($userInfo['terminal'], [UserTerminalEnum::WECHAT_MMP, UserTerminalEnum::WECHAT_OA])) {
            $auth = self::hasWechatAuth($userInfo);
            $user['is_auth'] = $auth ? YesNoEnum::YES : YesNoEnum::NO;
        }

        $user['has_password'] = !empty($user['password']);
        $user->hidden(['password']);

        //是否是会员
        $user['is_member'] = 0;
        $user['member_end_time_ts'] = 0;
        if (!empty($user['member_end_time'])) {
            $user['member_end_time_ts'] = $user['member_end_time'] * 1000;
            if ($user['member_end_time'] > time()) {
                $user['is_member'] = 1;
            }
            $user['member_end_time'] = date('Y-m-d H:i:s', $user['member_end_time']);
        } elseif (isset($user['member_perpetual']) && $user['member_perpetual']) {
            $user['is_member'] = 1;
            $user['member_end_time'] = '永久会员';
        }

        $user_member = UserMember::field('is_perpetual,package_name,member_end_time,package_info')
            ->json(['package_info'], true)
            ->where([
                'user_id' => $userInfo['user_id'], 'refund_status' => PayEnum::REFUND_NOT
            ])
            ->order(['is_perpetual' => 'desc', 'add_member_time' => 'desc', 'id' => 'desc'])
            ->findOrEmpty()->toArray();

        $user_member['member_end_time_ts'] = 0;
        if (!empty($user_member) && (!empty($user['member_end_time']) || $user['member_perpetual'])) {
            $user_member['member_end_time_ts'] = $user_member['member_end_time'] * 1000;
            $user['member_end_time'] = $user_member['is_perpetual'] == 1 ? '永久' : date('Y-m-d H:i:s', $user_member['member_end_time']);
        }

        $retData = $user->toArray();
        $retData['member_info'] = $user_member;
        return $retData;
    }


    /**
     * @notes 个人信息
     * @param $userId
     * @return array
     * @author 段誉
     * @date 2022/9/20 19:45
     */
    public static function info($userInfo)
    {
        $user = User::where(['id' => $userInfo['user_id']])
            ->field('id,sn,sex,account,password,nickname,real_name,avatar,mobile,create_time,balance,balance_draw')
            ->findOrEmpty();
        $user['has_password'] = !empty($user['password']);
        $user['has_auth'] = self::hasWechatAuth($userInfo);
        $user['version'] = config('project.version');
        $user->hidden(['password']);
        $user['is_cancelled'] = ConfigService::get('user_config', 'is_cancelled', 0);
        return $user->toArray();
    }


    /**
     * @notes 设置用户信息
     * @param int $userId
     * @param array $params
     * @return User|false
     * @author 段誉
     * @date 2022/9/21 16:53
     */
    public static function setInfo(int $userId, array $params)
    {
        try {
            return User::update([
                    'id' => $userId,
                    $params['field'] => $params['value']]
            );
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }

    public static function completeInfo(int $userId, $avatar, $nickname)
    {
        try {
            return User::update([
                    'id' => $userId,
                    'avatar' => $avatar,
                    'nickname' => $nickname,
                ]
            );
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 是否有微信授权信息
     * @param $userId
     * @return bool
     * @author 段誉
     * @date 2022/9/20 19:36
     */
    public static function hasWechatAuth($userInfo)
    {
        //是否有微信授权登录
        $auth = false;
        if (in_array($userInfo['terminal'], [UserTerminalEnum::WECHAT_MMP, UserTerminalEnum::WECHAT_OA])) {
            $auth_terminal = $userInfo['terminal'] == UserTerminalEnum::WECHAT_OA ? [UserTerminalEnum::PC, UserTerminalEnum::WECHAT_OA] : $userInfo['terminal'];
            $UserAuth = UserAuth::where(['user_id' => $userInfo['user_id'], 'terminal' => $auth_terminal])->findOrEmpty();
            if (!$UserAuth->isEmpty()) {
                $auth = true;
            }
        }
        return $auth;
    }

    static function getOpenID($userInfo)
    {
        $userID = $userInfo['user_id'] ?? ($userInfo['id'] ?? '');
        $terminal = $userInfo['terminal'] ?? ($userInfo['channel'] ?? '');
        if (empty($userID) || empty($terminal)) {
            throw new \InvalidArgumentException('请传入正确的用户信息');
        }

        $openid = '';
        if ($terminal == UserTerminalEnum::WECHAT_MMP) {
            $openid = UserAuth::where(['user_id' => $userID, 'terminal' => $terminal])->value("openid");
        }
        if ($terminal == UserTerminalEnum::DOUYIN_MMP) {
            $openid = UserAuthDy::where(['user_id' => $userID, 'terminal' => $terminal])->value("openid");
        }
        return $openid;
    }


    /**
     * @notes 重置登录密码
     * @param $params
     * @return bool
     * @author 段誉
     * @date 2022/9/16 18:06
     */
    public static function resetPassword(array $params)
    {
        try {
            // 校验验证码
            $smsDriver = new SmsDriver();
            if (!$smsDriver->verify($params['mobile'], $params['code'], NoticeEnum::FIND_LOGIN_PASSWORD_CAPTCHA)) {
                throw new \Exception('Wrong verification code');
            }

            // 重置密码
            $passwordSalt = Config::get('project.unique_identification');
            $password = create_password($params['password'], $passwordSalt);

            // 更新
            User::where('mobile', $params['mobile'])->update([
                'password' => $password
            ]);

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 修稿密码
     * @param $params
     * @param $userId
     * @return bool
     * @author 段誉
     * @date 2022/9/20 19:13
     */
    public static function changePassword(array $params, int $userId)
    {
        try {
            $user = User::findOrEmpty($userId);
            if ($user->isEmpty()) {
                throw new \Exception('用户不存在');
            }

            // 密码盐
            $passwordSalt = Config::get('project.unique_identification');

            if (!empty($user['password'])) {
                if (empty($params['old_password'])) {
                    throw new \Exception('请填写旧密码');
                }
                $oldPassword = create_password($params['old_password'], $passwordSalt);
                if ($oldPassword != $user['password']) {
                    throw new \Exception('原密码不正确');
                }
            }

            // 保存密码
            $password = create_password($params['password'], $passwordSalt);
            $user->password = $password;
            $user->save();

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 获取小程序手机号
     * @param array $params
     * @return bool
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @author 段誉
     * @date 2023/2/27 11:49
     */
    public static function getMobileByMnp(array $params)
    {
        try {
            $response = (new WeChatMnpService())->getUserPhoneNumber($params['code']);
            $phoneNumber = $response['phone_info']['purePhoneNumber'] ?? '';
            if (empty($phoneNumber)) {
                throw new \Exception('获取手机号码失败');
            }

            $user = User::where([
                ['mobile', '=', $phoneNumber],
                ['id', '<>', $params['user_id']]
            ])->findOrEmpty();

            if (!$user->isEmpty()) {
                throw new \Exception('手机号已被其他账号绑定');
            }

            // 绑定手机号
            User::update([
                'id' => $params['user_id'],
                'mobile' => $phoneNumber
            ]);

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 绑定手机号
     * @param $params
     * @return bool
     * @author 段誉
     * @date 2022/9/21 17:28
     */
    public static function bindMobile(array $params)
    {
        try {
            // 变更手机号场景
            $sceneId = NoticeEnum::CHANGE_MOBILE_CAPTCHA;
            $where = [
                ['id', '=', $params['user_id']],
                ['mobile', '=', $params['mobile']]
            ];

            // 绑定手机号场景
            if ($params['type'] == 'bind') {
                $sceneId = NoticeEnum::BIND_MOBILE_CAPTCHA;
                $where = [
                    ['mobile', '=', $params['mobile']]
                ];
            }

            // 校验短信
            $checkSmsCode = (new SmsDriver())->verify($params['mobile'], $params['code'], $sceneId);
            if (!$checkSmsCode) {
                throw new \Exception('Wrong verification code');
            }

            $user = User::where($where)->findOrEmpty();
            if (!$user->isEmpty()) {
                throw new \Exception('该手机号已被使用');
            }

            User::update([
                'id' => $params['user_id'],
                'mobile' => $params['mobile'],
            ]);

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    // 内部测试人员
    public static function internalTestingMember()
    {
        return ConfigService::getFromCache('internal_testing_member', 'default');
    }

    public static function isinternalTestingMember($userID)
    {
        $memberList = self::internalTestingMember();
        if (is_array($memberList) && count($memberList)) {
            return in_array($userID, $memberList);
        }
        return false;
    }

    // 内部测试人员 永久开启免费体验
    public static function alwaysAllowFreeTrialMember()
    {
        return ConfigService::getFromCache('always_allow_free_trail_member', 'default');
    }

    public static function isAlwaysAllowFreeTrialMember($userID)
    {
        $memberList = self::alwaysAllowFreeTrialMember();
        if (is_array($memberList) && count($memberList)) {
            return in_array($userID, $memberList);
        }
        return false;
    }

    public static function userDeleteAccoutCoolDownTime($userID)
    {
        if (self::isinternalTestingMember($userID)) {
            return 3 * 60;
        }
        // 用户注销再次注册的CD时间
        return 10 * 86400;
    }

}
