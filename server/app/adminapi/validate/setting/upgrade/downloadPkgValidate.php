<?php
// +----------------------------------------------------------------------
// | likeshop100%开源免费商用商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshopTeam
// +----------------------------------------------------------------------

namespace app\adminapi\validate\setting\upgrade;


use app\adminapi\logic\setting\system\UpgradeLogic;
use app\common\validate\BaseValidate;

/**
 * 系统更新-获取更新包链接
 * Class UpgradeValidate
 * @package app\adminapi\validate
 */
class downloadPkgValidate extends BaseValidate
{

    protected $rule = [
        'id'            => 'require|checkVersionData',
        'update_type'   => 'require'
    ];


    protected $message = [
        'id.require'            => '参数缺失',
        'update_type.require'   => '参数缺失',
    ];


    /**
     * @notes 验证版本信息
     * @param $value
     * @param $reule
     * @param $data
     * @return bool|string
     * @author 段誉
     * @date 2021/10/9 15:05
     */
    protected function checkVersionData($value, $reule, $data)
    {
        //目标更新版本信息
        $targetVersionData = UpgradeLogic::getVersionDataById($value);

        if (empty($targetVersionData)) {
            return '未获取到对应版本信息';
        }
        return true;
    }
}