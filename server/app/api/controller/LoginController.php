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

namespace app\api\controller;

use app\api\validate\{LoginAccountValidate, RegisterValidate, WebScanLoginValidate, LoginValidate};
use app\api\logic\LoginLogic;

/**
 * 登录注册
 * Class LoginController
 * @package app\api\controller
 */
class LoginController extends BaseApiController
{

    public array $notNeedLogin = [
        'register', 'account', 'logout', 'codeUrl', 'oaLogin', 'mnpLogin',
        'douyinMnpLogin', 'getScanCode', 'scanLogin', 'ticket', 'qrcode',
    ];

    /**
     * @notes 注册账号
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/7 15:38
     */
    public function register()
    {
        throw new \Exception("接口已停用");

        $params = (new RegisterValidate())->post()->goCheck('register');
        $result = LoginLogic::register($params);
        if (true === $result) {
            return $this->success('注册成功', [], 1, 1);
        }
        return $this->fail(LoginLogic::getError());
    }


    /**
     * @notes 账号密码/手机号密码/手机号验证码登录
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/16 10:42
     */
    public function account()
    {
        throw new \Exception("接口已停用");

        $params = (new LoginAccountValidate())->post()->goCheck();
        $result = LoginLogic::login($params);
        if (false === $result) {
            return $this->fail(LoginLogic::getError());
        }
        return $this->data($result);
    }

    public function emailLogin()
    {
        $params = (new LoginAccountValidate())->post()->goCheck();
        $result = LoginLogic::emailLogin($params);
        if (false === $result) {
            return $this->fail(LoginLogic::getError());
        }
        return $this->data($result);
    }

    /**
     * @notes 退出登录
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/16 10:42
     */
    public function logout()
    {
        LoginLogic::logout($this->userInfo);
        return $this->success();
    }


    /**
     * @notes 获取微信请求code的链接
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/15 18:27
     */
    public function codeUrl()
    {
        throw new \Exception("接口已停用");

        $url = $this->request->get('url');
        $codeUrl = LoginLogic::codeUrl($url);
        if (false === $codeUrl) {
            return $this->fail(LoginLogic::getError());
        }
        return $this->success('获取成功', ['url' => $codeUrl]);
    }


    /**
     * @notes 公众号登录
     * @return \think\response\Json
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author 段誉
     * @date 2022/9/20 19:48
     */
    public function oaLogin()
    {
        throw new \Exception("接口已停用");

        $params = (new LoginValidate())->post()->goCheck('oa');
        $res = LoginLogic::oaLogin($params);
        if (false === $res) {
            return $this->fail(LoginLogic::getError());
        }
        return $this->success('', $res);
    }


    /**
     * @notes 小程序-登录接口
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/20 19:48
     */
    public function mnpLogin()
    {
        $params = (new LoginValidate())->post()->goCheck('mnpLogin');
        $res = LoginLogic::mnpLogin($params);
        if (false === $res) {
            return $this->fail(LoginLogic::getError());
        }
        return $this->success('Success', $res);
    }

    public function douyinMnpLogin()
    {
        $res = LoginLogic::douyinMnpLogin($this->request->all());
        if (false === $res) {
            return $this->fail(LoginLogic::getError());
        }
        return $this->success('Success', $res);
    }

    /**
     * @notes 小程序绑定微信
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/20 19:48
     */
    public function mnpAuthBind()
    {
        throw new \Exception("接口已停用");

        $params = (new LoginValidate())->post()->goCheck("wechatAuth");
        $params['user_id'] = $this->userId;
        $result = LoginLogic::mnpAuthLogin($params);
        if ($result === false) {
            return $this->fail(LoginLogic::getError());
        }
        return $this->success('绑定成功', [], 1, 1);
    }


    /**
     * @notes 公众号绑定微信
     * @return \think\response\Json
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author 段誉
     * @date 2022/9/20 19:48
     */
    public function oaAuthBind()
    {
        throw new \Exception("接口已停用");

        $params = (new LoginValidate())->post()->goCheck("wechatAuth");
        $params['user_id'] = $this->userId;
        $result = LoginLogic::oaAuthLogin($params);
        if ($result === false) {
            return $this->fail(LoginLogic::getError());
        }
        return $this->success('绑定成功', [], 1, 1);
    }


    /**
     * @notes 获取扫码地址
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/10/20 18:25
     */
    public function getScanCode()
    {
        throw new \Exception("接口已停用");

        $redirectUri = $this->request->get('url/s');
        $result = LoginLogic::getScanCode($redirectUri);
        if (false === $result) {
            return $this->fail(LoginLogic::getError() ?? '未知错误');
        }
        return $this->success('', $result);
    }


    /**
     * @notes 网站扫码登录
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/10/21 10:28
     */
    public function scanLogin()
    {
        throw new \Exception("接口已停用");

        $params = (new WebScanLoginValidate())->post()->goCheck();
        $result = LoginLogic::scanLogin($params);
        if (false === $result) {
            return $this->fail(LoginLogic::getError() ?? '登录失败');
        }
        return $this->success('', $result);
    }


    /**
     * @notes 更新用户头像昵称
     * @return \think\response\Json
     * @author 段誉
     * @date 2023/2/22 11:15
     */
    public function updateUser()
    {
        throw new \Exception("接口已停用");

        $params = (new LoginValidate())->post()->goCheck("updateUser");
        LoginLogic::updateUser($params, $this->userId);
        return $this->success('操作成功', [], 1, 1);
    }


    /**
     * @notes 扫码验证
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/7/25 15:37
     */
    public function ticket()
    {
        throw new \Exception("接口已停用");

        $key = $this->request->post('key');
        $result = LoginLogic::checkTicket($key);
        return $this->success($result['msg'], $result['data'], $result['code'], $result['show']);
    }


    /**
     * @notes 获取登录二维码
     * @author 段誉
     * @date 2021/10/21 14:46
     */
    public function qrcode()
    {
        throw new \Exception("接口已停用");

        $params = $this->request->get();
        $result = LoginLogic::getLoginCode($params);
        if (false === $result) {
            return $this->fail(LoginLogic::getError());
        }
        return $this->success('', $result);
    }

    /**
     * @notes 注销账号
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/5/25 4:12 下午
     */
    public function cancelled()
    {
        if (env('zone') == 'domestic') {
            throw new \Exception("接口已停用");
        }

        $params = $this->request->post();
        $result = LoginLogic::cancelled($params, $this->userInfo);
        if (true !== $result) {
            return $this->fail($result);
        }
        return $this->success('', [], 1, 1);
    }
}
