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
namespace app\adminapi\controller\channel;
use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\channel\PcSettingLogic;

/**
 * pc端设置控制器类
 * Class PcSettingController
 * @package app\adminapi\lists\channel
 */
class PcSettingController extends BaseAdminController
{

    /**
     * @notes 获取pc端设置
     * @author cjhao
     * @date 2023/4/19 15:25
     */
    public function getConfig()
    {
        $config = (new PcSettingLogic())->getConfig();
        return $this->success('',$config);
    }


    /**
     * @notes 设置pc端设置
     * @return \think\response\Json
     * @author cjhao
     * @date 2023/4/19 15:27
     */
    public function setConfig()
    {
        (new PcSettingLogic())->setConfig($this->request->post());
        return $this->success('操作成功');
    }

}