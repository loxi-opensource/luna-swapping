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

namespace app\adminapi\logic\setting\web;


use app\common\logic\BaseLogic;
use app\common\service\ConfigService;
use app\common\service\FileService;


/**
 * 网站设置
 * Class WebSettingLogic
 * @package app\adminapi\logic\setting
 */
class WebSettingLogic extends BaseLogic
{

    /**
     * @notes 获取网站信息
     * @return array
     * @author 段誉
     * @date 2021/12/28 15:43
     */
    public static function getWebsiteInfo(): array
    {
        return [
            'name' => ConfigService::get('website', 'name'),                                        //网站名称
            'web_favicon' => FileService::getFileUrl(ConfigService::get('website', 'web_favicon')), //网站图标
            'web_logo' => FileService::getFileUrl(ConfigService::get('website', 'web_logo')),       //网站logo
            'login_image' => FileService::getFileUrl(ConfigService::get('website', 'login_image')), //网站登录页广告图
            'shop_name' => ConfigService::get('website', 'shop_name'),                              //前台名称
            'shop_logo' => FileService::getFileUrl(ConfigService::get('website', 'shop_logo')),     //前台logo

            'pc_logo' => FileService::getFileUrl(ConfigService::get('website', 'pc_logo')),         //pc端logo
            'pc_title' => ConfigService::get('website', 'pc_title'),         //pc端标题
            'pc_ico' => FileService::getFileUrl(ConfigService::get('website', 'pc_ico')),         //pc端图标
            'pc_desc' => ConfigService::get('website', 'pc_desc',''),         //pc端描述
            'pc_key' => ConfigService::get('website', 'pc_key',''),         //pc端关键词
            'pc_login_image' => empty(ConfigService::get('website', 'pc_login_image')) ? '' : FileService::getFileUrl(ConfigService::get('website', 'pc_login_image')),         //pc端登录封面

            'contacts' => ConfigService::get('website', 'contacts', ''),              //联系人
            'mobile' =>  ConfigService::get('website', 'mobile', ''),           //手机号码
        ];
    }


    /**
     * @notes 设置网站信息
     * @param array $params
     * @author 段誉
     * @date 2021/12/28 15:43
     */
    public static function setWebsiteInfo(array $params)
    {
        $favicon = FileService::setFileUrl($params['web_favicon']);
        $logo = FileService::setFileUrl($params['web_logo']);
        $login = FileService::setFileUrl($params['login_image']);
        $shopLogo = FileService::setFileUrl($params['shop_logo']);
        $pcLogo = FileService::setFileUrl($params['pc_logo']);
        $pcIco = FileService::setFileUrl($params['pc_ico']);
        $pcLoginImage = FileService::setFileUrl($params['pc_login_image']);

        ConfigService::set('website', 'name', $params['name']);
        ConfigService::set('website', 'web_favicon', $favicon);
        ConfigService::set('website', 'web_logo', $logo);
        ConfigService::set('website', 'login_image', $login);
        ConfigService::set('website', 'shop_name', $params['shop_name']);
        ConfigService::set('website', 'shop_logo', $shopLogo);
        
        ConfigService::set('website', 'pc_logo', $pcLogo);
        ConfigService::set('website', 'pc_title', $params['pc_title']);
        ConfigService::set('website', 'pc_ico', $pcIco);
        ConfigService::set('website', 'pc_desc', $params['pc_desc']);
        ConfigService::set('website', 'pc_key', $params['pc_key']);
        ConfigService::set('website', 'pc_login_image', $pcLoginImage);

        ConfigService::set('website', 'contacts', $params['contacts']);
        ConfigService::set('website', 'mobile', $params['mobile'] );

    }


    /**
     * @notes 获取版权备案
     * @return array
     * @author 段誉
     * @date 2021/12/28 16:09
     */
    public static function getCopyright() : array
    {
        return ConfigService::get('copyright', 'config', []);
    }


    /**
     * @notes 设置版权备案
     * @param array $params
     * @return bool
     * @author 段誉
     * @date 2022/8/8 16:33
     */
    public static function setCopyright(array $params)
    {
        try {
            if (!is_array($params['config'])) {
                throw new \Exception('参数异常');
            }
            ConfigService::set('copyright', 'config', $params['config'] ?? []);
            return true;
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 设置政策协议
     * @param array $params
     * @author ljj
     * @date 2022/2/15 10:59 上午
     */
    public static function setAgreement(array $params)
    {
        $serviceContent = clear_file_domain($params['service_content'] ?? '');
        $privacyContent = clear_file_domain($params['privacy_content'] ?? '');
        $payContent = clear_file_domain($params['pay_content'] ?? '');
        $distributionContent = clear_file_domain($params['distribution_content'] ?? '');
        ConfigService::set('agreement', 'service_title', $params['service_title'] ?? '');
        ConfigService::set('agreement', 'service_status', $params['service_status'] ?? 0);
        ConfigService::set('agreement', 'service_content', $serviceContent);
        ConfigService::set('agreement', 'privacy_title', $params['privacy_title'] ?? '');
        ConfigService::set('agreement', 'privacy_status', $params['privacy_status'] ?? 0);
        ConfigService::set('agreement', 'privacy_content', $privacyContent);
        ConfigService::set('agreement', 'pay_title', $params['pay_title'] ?? '');
        ConfigService::set('agreement', 'pay_status', $params['pay_status'] ?? 0);
        ConfigService::set('agreement', 'pay_content', $payContent);
        ConfigService::set('agreement', 'distribution_title', $params['distribution_title'] ?? '');
        ConfigService::set('agreement', 'distribution_status', $params['distribution_status'] ?? 0);
        ConfigService::set('agreement', 'distribution_content', $distributionContent);
    }


    /**
     * @notes 获取政策协议
     * @return array
     * @author ljj
     * @date 2022/2/15 11:15 上午
     */
    public static function getAgreement() : array
    {
        $config = [
            'service_title' => ConfigService::get('agreement', 'service_title',''),
            'service_status' => ConfigService::get('agreement', 'service_status',0),
            'service_content' => ConfigService::get('agreement', 'service_content',''),
            'privacy_title' => ConfigService::get('agreement', 'privacy_title',''),
            'privacy_status' => ConfigService::get('agreement', 'privacy_status',0),
            'privacy_content' => ConfigService::get('agreement', 'privacy_content',''),
            'pay_title' => ConfigService::get('agreement', 'pay_title',''),
            'pay_status' => ConfigService::get('agreement', 'pay_status',0),
            'pay_content' => ConfigService::get('agreement', 'pay_content',''),
            'distribution_title' => ConfigService::get('agreement', 'distribution_title',''),
            'distribution_status' => ConfigService::get('agreement', 'distribution_status',0),
            'distribution_content' => ConfigService::get('agreement', 'distribution_content',''),
        ];

        $config['service_content'] = get_file_domain($config['service_content']);
        $config['privacy_content'] = get_file_domain($config['privacy_content']);
        $config['pay_content'] = get_file_domain($config['pay_content']);
        $config['distribution_content'] = get_file_domain($config['distribution_content']);

        return $config;
    }

    /**
     * @notes 获取公告配置
     * @return array
     * @author ljj
     * @date 2023/7/6 4:06 下午
     */
    public static function getBulletinConfig()
    {
        return [
            // 公告弹窗
            'is_bulletin' => ConfigService::get('bulletin_config', 'is_bulletin', 0),
            // 公告标题
            'bulletin_title' => ConfigService::get('bulletin_config', 'bulletin_title'),
            // 公告内容
            'bulletin_content' => ConfigService::get('bulletin_config', 'bulletin_content'),
        ];
    }

    /**
     * @notes 设置公告配置
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/7/6 4:10 下午
     */
    public static function setBulletinConfig($params)
    {
        ConfigService::set('bulletin_config', 'is_bulletin', $params['is_bulletin']);
        ConfigService::set('bulletin_config', 'bulletin_title', $params['bulletin_title']);
        ConfigService::set('bulletin_config', 'bulletin_content', $params['bulletin_content']);
        return true;
    }
}