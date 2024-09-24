<?php
// +----------------------------------------------------------------------
// | likeshop开源商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop系列产品在gitee、github等公开渠道开源版本可免费商用，未经许可不能去除前后端官方版权标识
// |  likeshop系列产品收费版本务必购买商业授权，购买去版权授权后，方可去除前后端官方版权标识
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | likeshop团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshop.cn.team
// +----------------------------------------------------------------------

namespace app\adminapi\controller\task;


use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\task\TaskInviteLists;
use app\adminapi\logic\task\TaskInviteLogic;
use app\adminapi\validate\task\TaskInviteValidate;

class TaskInviteController extends BaseAdminController
{
    /**
     * @notes 获取邀请配置
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/14 7:02 下午
     */
    public function getConfig()
    {
        $result = (new TaskInviteLogic())->getConfig();
        return $this->data($result);
    }

    /**
     * @notes 设置邀请配置
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/14 7:04 下午
     */
    public function setConfig()
    {
        $params = (new TaskInviteValidate())->post()->goCheck('setConfig');
        (new TaskInviteLogic())->setConfig($params);
        return $this->success('设置成功', [], 1, 1);
    }

    /**
     * @notes 邀请记录列表
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/17 10:22 上午
     */
    public function lists()
    {
        return $this->dataLists(new TaskInviteLists());
    }
}