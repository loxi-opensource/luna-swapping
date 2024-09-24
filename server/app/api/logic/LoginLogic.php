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

use app\api\service\{
    DouyinUserService,
    UserTokenService,
    WechatUserService
};
use app\common\cache\WebScanLoginCache;
use app\common\enum\{LoginEnum, QueueNameConst, user\AccountLogEnum, user\UserTerminalEnum, YesNoEnum};
use app\common\logic\AccountLogLogic;
use app\common\logic\BaseLogic;
use app\common\model\user\{User, UserAuth, UserAuthDy};
use app\common\service\{ConfigService,
    douyin\DouyinMnpService,
    FileService,
    wechat\WeChatConfigService,
    wechat\WeChatMnpService,
    wechat\WeChatOaService,
    wechat\WeChatRequestService
};
use EasyWeChat\OfficialAccount\Application;
use think\facade\{Config, Db, Log};

/**
 * 登录逻辑
 * Class LoginLogic
 * @package app\api\logic
 */
class LoginLogic extends BaseLogic
{

    /**
     * @notes 账号密码注册
     * @param array $params
     * @return bool
     * @author 段誉
     * @date 2022/9/7 15:37
     */
    public static function register(array $params)
    {
        try {
            $userSn = User::createUserSn();
            $passwordSalt = Config::get('project.unique_identification');
            $password = create_password($params['password'], $passwordSalt);
            $avatar = ConfigService::get('default_image', 'user_avatar');

            //注册奖励
            $register_reward_status = ConfigService::get('register_reward', 'status');
            $balance = 0;
            if ($register_reward_status == 1) {
                $balance = ConfigService::get('register_reward', 'reward');
                if (empty($balance) || $balance < 0) {
                    $balance = 0;
                }
            }

            $user = User::create([
                'sn' => $userSn,
                'avatar' => $avatar,
                'nickname' => '用户' . $userSn,
                'account' => $params['account'],
                'password' => $password,
                'channel' => $params['channel'],
                'mobile' => $params['account'],
                'balance' => $balance,
                'is_new_user' => YesNoEnum::YES,
            ]);

            if ($balance > 0) {
                // 记录账户流水
                AccountLogLogic::add(
                    $user->id,
                    AccountLogEnum::UM_INC_REGISTER,
                    AccountLogEnum::INC,
                    $balance
                );
            }

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 账号/手机号登录，手机号验证码
     * @param $params
     * @return array|false
     * @author 段誉
     * @date 2022/9/6 19:26
     */
    public static function login($params)
    {
        try {
            // 手机号 密码登录
            $where = ['mobile' => $params['account']];
            if ($params['scene'] == LoginEnum::MOBILE_LOGIN) {
                //手机验证码登录
                $where = ['mobile' => $params['account']];
            }

            $user = User::where($where)->findOrEmpty();
            if ($user->isEmpty()) {
                throw new \Exception('用户不存在');
            }

            //更新登录信息
            $user->login_time = time();
            $user->login_ip = request()->ip();
            $user->save();

            //设置token
            $userInfo = UserTokenService::setToken($user->id, $params['terminal']);

            //返回登录信息
            $avatar = $user->avatar ?: Config::get('project.default_image.user_avatar');
            $avatar = FileService::getFileUrl($avatar);

            return [
                'nickname' => $userInfo['nickname'],
                'sn' => $userInfo['sn'],
                'mobile' => $userInfo['mobile'],
                'avatar' => $avatar,
                'token' => $userInfo['token'],
            ];
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 退出登录
     * @param $userInfo
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/16 17:56
     */
    public static function logout($userInfo)
    {
        //token不存在，不注销
        if (!isset($userInfo['token'])) {
            return false;
        }

        //设置token过期
        return UserTokenService::expireToken($userInfo['token']);
    }


    /**
     * @notes 获取微信请求code的链接
     * @param string $url
     * @return string
     * @author 段誉
     * @date 2022/9/20 19:47
     */
    public static function codeUrl(string $url)
    {
        try {
            return (new WeChatOaService())->getCodeUrl($url);

        } catch (\Exception $e) {
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 公众号登录
     * @param array $params
     * @return array|false
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author 段誉
     * @date 2022/9/20 19:47
     */
    public static function oaLogin(array $params)
    {
        Db::startTrans();
        try {
            $terminal = UserTerminalEnum::WECHAT_OA;
            if (isset($params['key']) && !empty($params['key'])) {
                $terminal = UserTerminalEnum::PC;
            }

            //通过code获取微信 openid
            $response = (new WeChatOaService())->getOaResByCode($params['code']);
            $userServer = new WechatUserService($response, $terminal);
            $userInfo = $userServer->getResopnseByUserInfo()->authUserLogin()->getUserInfo();

            // 更新登录信息
            self::updateLoginInfo($userInfo['id']);

            // 设置PC缓存token并标记已登录
            if (isset($params['key']) && !empty($params['key'])) {
                self::setIsLogin($params['key'], $userInfo['id']);
            }

            Db::commit();
            return $userInfo;

        } catch (\Exception $e) {
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 小程序-静默登录
     * @param array $params
     * @return array|false
     * @author 段誉
     * @date 2022/9/20 19:47
     */
    public static function silentLogin(array $params)
    {
        try {
            //通过code获取微信 openid
            $response = (new WeChatMnpService())->getMnpResByCode($params['code']);
            $userServer = new WechatUserService($response, UserTerminalEnum::WECHAT_MMP);
            $userInfo = $userServer->getResopnseByUserInfo('silent')->getUserInfo();

            if (!empty($userInfo)) {
                // 更新登录信息
                self::updateLoginInfo($userInfo['id']);
            }

            return $userInfo;
        } catch (\Exception  $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 小程序-授权登录
     * @param array $params
     * @return array|false
     * @author 段誉
     * @date 2022/9/20 19:47
     */
    public static function mnpLogin(array $params)
    {
        Db::startTrans();
        try {
            //通过code获取微信 openid
            $response = (new WeChatMnpService())->getMnpResByCode($params['code']);
            $userServer = new WechatUserService($response, UserTerminalEnum::WECHAT_MMP);
            $userInfo = $userServer->getResopnseByUserInfo()->authUserLogin()->getUserInfo();

            // 更新登录信息
            self::updateLoginInfo($userInfo['id']);

            Db::commit();
            return $userInfo;
        } catch (\Exception  $e) {
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }
    }

    // 抖音小程序登录
    public static function douyinMnpLogin(array $params)
    {
        Db::startTrans();
        try {
            //通过code获取微信 openid
            $response = (new DouyinMnpService())->getMnpResByCode($params['code'], $params['anonymousCode']);

            $userServer = new DouyinUserService($response, UserTerminalEnum::DOUYIN_MMP);
            $userInfo = $userServer->getResopnseByUserInfo()->authUserLogin()->getUserInfo();

            // 更新登录信息
            self::updateLoginInfo($userInfo['id']);

            Db::commit();
            return $userInfo;
        } catch (\Exception  $e) {
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }
    }

    /**
     * @notes 更新登录信息
     * @param $userId
     * @throws \Exception
     * @author 段誉
     * @date 2022/9/20 19:46
     */
    public static function updateLoginInfo($userId)
    {
        $user = User::findOrEmpty($userId);
        if ($user->isEmpty()) {
            throw new \Exception('用户不存在');
        }

        $time = time();
        $user->login_time = $time;
        $user->login_ip = request()->ip();
        $user->update_time = $time;
        $user->save();
    }


    /**
     * @notes 小程序端绑定微信
     * @param array $params
     * @return bool
     * @author 段誉
     * @date 2022/9/20 19:46
     */
    public static function mnpAuthLogin(array $params)
    {
        try {
            //通过code获取微信openid
            $response = (new WeChatMnpService())->getMnpResByCode($params['code']);
            $response['user_id'] = $params['user_id'];
            $response['terminal'] = UserTerminalEnum::WECHAT_MMP;

            return self::createAuth($response);

        } catch (\Exception  $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 公众号端绑定微信
     * @param array $params
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author 段誉
     * @date 2022/9/16 10:43
     */
    public static function oaAuthLogin(array $params)
    {
        try {
            //通过code获取微信openid
            $response = (new WeChatOaService())->getOaResByCode($params['code']);
            $response['user_id'] = $params['user_id'];
            $response['terminal'] = UserTerminalEnum::WECHAT_OA;

            return self::createAuth($response);

        } catch (\Exception  $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 生成授权记录
     * @param $response
     * @return bool
     * @throws \Exception
     * @author 段誉
     * @date 2022/9/16 10:43
     */
    public static function createAuth($response)
    {
        //先检查openid是否有记录
        $isAuth = UserAuth::where('openid', '=', $response['openid'])->findOrEmpty();
        if (!$isAuth->isEmpty()) {
            throw new \Exception('微信已被绑定，请切换微信登录');
        }

        if (isset($response['unionid']) && !empty($response['unionid'])) {
            //在用unionid找记录，防止生成两个账号，同个unionid的问题
            $userAuth = UserAuth::where(['unionid' => $response['unionid']])
                ->findOrEmpty();
            if (!$userAuth->isEmpty() && $userAuth->user_id != $response['user_id']) {
                throw new \Exception('微信已被绑定，请切换微信登录');
            }
        }

        //如果没有授权，直接生成一条微信授权记录
        UserAuth::create([
            'user_id' => $response['user_id'],
            'openid' => $response['openid'],
            'unionid' => $response['unionid'] ?? '',
            'terminal' => $response['terminal'],
        ]);
        return true;
    }


    /**
     * @notes 获取扫码登录地址
     * @return array|false
     * @author 段誉
     * @date 2022/10/20 18:23
     */
    public static function getScanCode($redirectUri)
    {
        try {
            $config = WeChatConfigService::getOpConfig();
            $appId = $config['app_id'];
            $redirectUri = UrlEncode($redirectUri);

            // 设置有效时间标记状态, 超时扫码不可登录
            $state = MD5(time() . rand(10000, 99999));
            (new WebScanLoginCache())->setScanLoginState($state);

            // 扫码地址
            $url = WeChatRequestService::getScanCodeUrl($appId, $redirectUri, $state);
            return ['url' => $url];

        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 网站扫码登录
     * @param $params
     * @return array|false
     * @author 段誉
     * @date 2022/10/21 10:28
     */
    public static function scanLogin($params)
    {
        Db::startTrans();
        try {
            // 通过code 获取 access_token,openid,unionid等信息
            $userAuth = WeChatRequestService::getUserAuthByCode($params['code']);

            if (empty($userAuth['openid']) || empty($userAuth['access_token'])) {
                throw new \Exception('获取用户授权信息失败');
            }

            // 获取微信用户信息
            $response = WeChatRequestService::getUserInfoByAuth($userAuth['access_token'], $userAuth['openid']);

            // 生成用户或更新用户信息
            $userServer = new WechatUserService($response, UserTerminalEnum::PC);
            $userInfo = $userServer->getResopnseByUserInfo()->authUserLogin()->getUserInfo();

            // 更新登录信息
            self::updateLoginInfo($userInfo['id']);

            Db::commit();
            return $userInfo;

        } catch (\Exception $e) {
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 更新用户信息
     * @param $params
     * @param $userId
     * @return User
     * @author 段誉
     * @date 2023/2/22 11:19
     */
    public static function updateUser($params, $userId)
    {
        return User::where(['id' => $userId])->update([
            'nickname' => $params['nickname'],
            'avatar' => FileService::setFileUrl($params['avatar']),
            'is_new_user' => YesNoEnum::NO
        ]);
    }


    /**
     * @notes 验证是否已扫码
     * @param $key
     * @return array
     * @author 段誉
     * @date 2022/6/1 15:10
     */
    public static function checkTicket($key)
    {
        try {
            $data = [
                'user' => [],
                'status' => 0,// 未扫码-1 未登录-2 登录信息异常-3 登录成功-4
            ];

            if (empty($key)) {
                throw new \Exception('参数缺失');
            }

            $key_info = cache($key);
            if (empty($key_info)) {
                throw new \Exception('登录信息不存在');
            }

            if ($key_info['is_scan'] != 1) {
                // 未扫码
                $data['status'] = 1;
                return ['data' => $data, 'msg' => '未扫码', 'code' => 1, 'show' => 0];
            }

            if ($key_info['status'] != 1) {
                $data['status'] = 2;
                return ['data' => $data, 'msg' => '已扫码未登录', 'code' => 1, 'show' => 0];
            }

            // 加上cdn后，ip校验有问题，暂时关闭
//            if ($key_info['ip'] !== request()->ip()) {
//                $data['status'] = 3;
//                return ['data' => $data, 'msg' => '登录信息错误','code' => 1, 'show' => 0];
//            }

            // 返回token值
            $data['user'] = $key_info['token'] ?? '';
            $data['status'] = 4;
            return ['data' => $data, 'msg' => '', 'code' => 1, 'show' => 0];

        } catch (\Exception $e) {
            return ['data' => $data, 'msg' => $e->getMessage(), 'code' => 0, 'show' => 1];
        }
    }


    /**
     * @notes 获取公众号带参二维码
     * @return array|false
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @author 段誉
     * @date 2021/10/21 15:02
     */
    public static function getLoginCode($params = [])
    {
        try {
            $config = WeChatConfigService::getOaConfig();
            $app = new Application($config);
            $api = $app->getClient();

            $accessToken = $app->getAccessToken();
            $access_token = $accessToken->getToken();

            $key = self::setKey($params['invite_code'] ?? '')['key'];
            $create_qrcode = $api->post('/cgi-bin/qrcode/create?access_token=' . $access_token, [
                'body' => \json_encode([
                    "expire_seconds" => 120,
                    "action_name" => 'QR_STR_SCENE',
                    "action_info" => ['scene' => ['scene_str' => $key]],
                ])
            ]);
            $create_qrcode = json_decode($create_qrcode, true);
            if (!isset($create_qrcode['ticket'])) {
                if (isset($create_qrcode['errcode']) == 40001) {
                    //刷新access_token
                    $access_token = $accessToken->refresh();

                    $create_qrcode = $api->post('/cgi-bin/qrcode/create?access_token=' . $access_token, [
                        'body' => \json_encode([
                            "expire_seconds" => 120,
                            "action_name" => 'QR_STR_SCENE',
                            "action_info" => ['scene' => ['scene_str' => $key]],
                        ])
                    ]);
                    $create_qrcode = json_decode($create_qrcode, true);

                    if (!isset($create_qrcode['ticket'])) {
                        Log::write('二维码生成失败：' . json_encode($create_qrcode));
                        throw new \Exception("生成失败！请稍后重试");
                    }
                } else {
                    Log::write('二维码生成失败：' . json_encode($create_qrcode));
                    throw new \Exception("生成失败！请稍后重试");
                }
            }

            return [
                'expire_seconds' => 120,
                'url' => 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $create_qrcode['ticket'],
                'key' => $key,
                'ticket' => $create_qrcode['ticket']
            ];

        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 设置当前用户key
     * @return array
     * @author 段誉
     * @date 2021/10/21 14:49
     */
    public static function setKey($inviteCode)
    {
        $unid = uniqid();
        $ip = request()->ip();
        session_start();
        $session_id = session_id();
        $temp_key = md5($session_id . $ip . $unid);

        $data = [
            "key" => $temp_key,
            "status" => 0,
            "session_id" => $session_id,
            "salt" => $unid,
            "create_time" => time(),
            "ip" => $ip,
            'is_scan' => 0,
            'is_login' => 0,
            'token' => [],
            'invite_code' => $inviteCode
        ];
        cache($temp_key, $data, 120);
        return $data;
    }


    /**
     * @notes 设置为已登录状态
     * @param $key
     * @param $user_id
     * @return bool
     * @author 段誉
     * @date 2022/6/1 15:10
     */
    public static function setIsLogin($key, $user_id)
    {
        if (empty($key)) {
            return false;
        }

        $oldData = cache($key);
        //重置缓存信息
        $data = [
            'token' => UserTokenService::setToken($user_id, UserTerminalEnum::PC),
            "key" => $key,
            "status" => 1, // 登录状态
            "session_id" => $oldData['session_id'],
            "salt" => $oldData['salt'],
            "create_time" => time(),
            "ip" => $oldData['ip'],
            'is_scan' => 1, // 扫码状态
            'is_login' => 1, // 是否已获取到token
            'invite_code' => $oldData['invite_code'] ?? ''
        ];
        cache($key, $data, 120);
        return true;
    }


    /**
     * @notes 注销账号
     * @param $params
     * @param $userInfo
     * @return bool|string
     * @author ljj
     * @date 2023/5/25 4:12 下午
     */
    public static function cancelled($params, $userInfo)
    {
        Db::startTrans();
        try {
            $is_cancelled = ConfigService::get('user_config', 'is_cancelled', 0);
            if (!$is_cancelled) {
                throw new \think\Exception('后台已经关闭注销账号功能', 10006);
            }

            //设置token过期
            if (isset($userInfo['token'])) {
                UserTokenService::expireToken($userInfo['token']);
            }

            //删除账号
            User::update(['cancelled_remark' => $params['cancelled_remark'] ?? ''], ['id' => $userInfo['user_id']]);
            User::destroy($userInfo['user_id']);

            //删除第三方授权
            if (env('zone') == 'domestic') {
                UserAuth::where(['user_id' => $userInfo['user_id']])->delete();
                UserAuthDy::where(['user_id' => $userInfo['user_id']])->delete();
            }

            // 提交事务
            Db::commit();
            Log::record("用户注销账号" . json_encode(compact('params', 'userInfo'), JSON_UNESCAPED_UNICODE));

            return true;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $e->getMessage();
        }
    }
}
