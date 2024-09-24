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
namespace app\common\service\wechat;


use app\common\logic\BaseLogic;
use EasyWeChat\Kernel\Exceptions\Exception;
use EasyWeChat\MiniApp\Application;


/**
 * 微信功能类
 * Class WeChatMnpService
 * @package app\common\service
 */
class WeChatMnpService extends BaseLogic
{

    protected $app;

    protected $config;

    public function __construct()
    {
        $this->config = $this->getConfig();
        $this->app = new Application($this->config);
    }


    /**
     * @notes 配置
     * @return array
     * @throws \Exception
     * @author 段誉
     * @date 2023/2/27 12:03
     */
    protected function getConfig()
    {
        $config = WeChatConfigService::getMnpConfig();
        if (empty($config['app_id']) || empty($config['secret'])) {
            throw new \Exception('请先设置小程序配置');
        }
        return $config;
    }


    /**
     * @notes 小程序-根据code获取微信信息
     * @param string $code
     * @return array
     * @throws Exception
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @author 段誉
     * @date 2023/2/27 11:03
     */
    public function getMnpResByCode(string $code)
    {
        $utils = $this->app->getUtils();
        $response = $utils->codeToSession($code);

        if (!isset($response['openid']) || empty($response['openid'])) {
            throw new Exception('获取openID失败');
        }

        return $response;
    }


    /**
     * @notes 获取手机号
     * @param string $code
     * @return \EasyWeChat\Kernel\HttpClient\Response|\Symfony\Contracts\HttpClient\ResponseInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @author 段誉
     * @date 2023/2/27 11:46
     */
    public function getUserPhoneNumber(string $code)
    {
        return $this->app->getClient()->postJson('wxa/business/getuserphonenumber', [
            'code' => $code,
        ]);
    }


    /**
     * @notes 创建小程序码
     * @param array $param
     * @param string $type //返回类型：resource时返回资源类型,file保存并返回文件,base64返回base64
     * @return bool
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @author ljj
     * @date 2023/5/23 11:21 上午
     */
    public function createMpQrCode(array $param, string $type = 'resource')
    {
        try {
            //参数处理
            $page = $param['page'] ?? 'pages/index/index';
            $scene = $param['scene'] ?? 'null';
            $saveDir = $param['save_dir'] ?? 'uploads/qr_code/user_share/';
            $fileName = $param['file_name'] ?? time() . '.png';

            $response = $this->app->getClient()->postJson('/wxa/getwxacodeunlimit', [
                "scene" => $scene,//query 参数
                "page" => $page,//页面 page
                "check_path" => false,//检查page 是否存在
                "env_version" => "release",//小程序版本。正式版为 "release"，体验版为 "trial"，开发版为 "develop"
            ]);
            $isSuccessful = $response->isSuccessful();

            if (!$isSuccessful) {
                $result = $response->toArray();
                throw new Exception($result['errmsg']);
            }

            switch ($type) {
                case 'file':
                    $contents = $saveDir . $fileName;
                    $response->saveAs($contents);
                    break;
                case 'base64':
                    $getContent = $response->getContent();
                    $mpBase64 = chunk_split(base64_encode($getContent));
                    $contents = 'data:image/png;base64,' . $mpBase64;
                    break;
                case 'resource':
                    $contents = $response->getContent();
                    break;
            }

            return $contents;

        } catch (Exception $e) {
            self::$error = $e->getMessage();
            return false;

        }

    }

    public function getAccessToken()
    {
        $this->app->setCache(\think\facade\Cache::store('redis'));
        $accessToken = $this->app->getAccessToken()->getToken();
        return $accessToken;
    }

    public function sendTemplateMsg($templateID, $openID, $data, $page = null)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=' . $this->getAccessToken();
        // 点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转
        $templateContent = [
            'touser' => $openID,
            'template_id' => $templateID,
            'data' => $data
        ];
        if ($page) {
            $templateContent['page'] = $page;
        }
        return $this->app->getClient()->postJson($url, $templateContent);
    }
}
