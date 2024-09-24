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
namespace app\adminapi\logic\decorate;

use app\common\logic\BaseLogic;
use app\common\model\decorate\DecorateTabbar;
use app\common\service\ConfigService;
use app\common\service\FileService;


/**
 * 装修配置-底部导航
 * Class DecorateTabbarLogic
 * @package app\adminapi\logic\decorate
 */
class DecorateTabbarLogic extends BaseLogic
{

    /**
     * @notes 获取底部导航详情
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/7 16:58
     */
    public static function detail($type = 1): array
    {
//        $list = DecorateTabbar::getTabbarLists();
//        $style = ConfigService::get('tabbar', 'style', config('project.decorate.tabbar_style'));
        $decorateTabbar = DecorateTabbar::where(['type' => $type])->findOrEmpty()->toArray();
        return $decorateTabbar;
    }


    /**
     * @notes 底部导航保存
     * @param $params
     * @return bool
     * @throws \Exception
     * @author 段誉
     * @date 2022/9/7 17:19
     */
    public static function save($params): bool
    {
        DecorateTabbar::where(['id'=>$params['id']])->update(['data'=>$params['data']]);
        return true;
    }

}