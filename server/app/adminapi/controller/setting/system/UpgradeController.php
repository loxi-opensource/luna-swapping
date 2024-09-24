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


namespace app\adminapi\controller\setting\system;


use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\setting\system\UpgradeLists;
use app\adminapi\logic\setting\system\UpgradeLogic;
use app\adminapi\validate\setting\upgrade\downloadPkgValidate;
use app\adminapi\validate\setting\upgrade\UpgradeValidate;

/**
 * 系统更新
 * Class UpgradeController
 * @package app\adminapi\controller\settings\system
 */
class UpgradeController extends BaseAdminController
{


    /**
     * @notes 查看系统更新列表
     * @return \think\response\Json
     * @author 段誉
     * @date 2021/8/14 17:17
     */
    public function lists()
    {
        return $this->dataLists(new UpgradeLists());
    }


    /**
     * @notes 执行系统更新
     * @return \think\response\Json
     * @author 段誉
     * @date 2021/8/14 16:51
     */
    public function upgrade()
    {
        $params = (new UpgradeValidate())->post()->goCheck();
        $params['update_type'] = 1;//一键更新类型
        if (true === UpgradeLogic::upgrade($params)) {
            return $this->success('更新成功', [], 1, 1);
        }
        return $this->fail('更新失败:'. UpgradeLogic::getError());
    }


    /**
     * @notes 下载更新包
     * @return \think\response\Json
     * @author 段誉
     * @date 2021/10/8 14:23
     */
    public function downloadPkg()
    {
        $params = (new downloadPkgValidate())->post()->goCheck();
        $result = UpgradeLogic::getPkgLine($params);
        if (false === $result) {
            return $this->fail(UpgradeLogic::getError());
        }
        return $this->success('', $result);
    }


}