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

namespace app\adminapi\validate\setting;

use app\common\validate\BaseValidate;

/**
 * 网站设置验证器
 * Class WebSettingValidate
 * @package app\adminapi\validate\setting
 */
class WebSettingValidate extends BaseValidate
{
    protected $rule = [
        'name' => 'require|max:30',
        'web_favicon' => 'require',
        'web_logo' => 'require',
        'login_image' => 'require',
        'shop_name' => 'require',
        'shop_logo' => 'require',
        'pc_logo' => 'require',
        'pc_title' => 'require',
        'pc_ico' => 'require',
//        'pc_login_image' => 'require',
        'is_bulletin' => 'require|in:0,1',
        'bulletin_title' => 'requireIf:is_bulletin,1',
        'bulletin_content' => 'requireIf:is_bulletin,1',
    ];

    protected $message = [
        'name.require' => '请填写网站名称',
        'name.max' => '网站名称最长为12个字符',
        'web_favicon.require' => '请上传网站图标',
        'web_logo.require' => '请上传网站logo',
        'login_image.require' => '请上传登录页广告图',
        'shop_name.require' => '请填写前台名称',
        'shop_logo.require' => '请上传前台logo',
        'pc_logo.require' => '请上传PC端logo',
        'pc_title.require' => '请输入pc端网站标题',
        'pc_ico.require' => '请选择pc端网站图标',
//        'pc_login_image.require' => '请选择pc端登录封面',
        'is_bulletin.require' => '请选择公告弹窗',
        'is_bulletin.in' => '公告弹窗值错误',
        'bulletin_title.requireIf' => '请输入公告标题',
        'bulletin_content.requireIf' => '请输入公告内容',
    ];

    protected $scene = [
        'website' => ['name', 'web_favicon', 'web_logo', 'login_image', 'shop_name', 'shop_logo', 'pc_logo', 'pc_title', 'pc_ico'],
        'setBulletinConfig' => ['is_bulletin', 'bulletin_title', 'bulletin_content'],
    ];
}