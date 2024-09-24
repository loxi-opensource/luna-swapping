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

namespace app\adminapi\controller\recharge;


use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\recharge\RechargePackageLists;
use app\adminapi\logic\recharge\RechargePackageLogic;
use app\adminapi\validate\recharge\RechargePackageValidate;

class RechargePackageController extends BaseAdminController
{
    /**
     * @notes 获取充值套餐配置
     * @return mixed
     * @author ljj
     * @date 2023/6/28 11:00 上午
     */
    public function getConfig()
    {
        $result = (new RechargePackageLogic())->getConfig();
        return $this->success('',$result);
    }

    /**
     * @notes 设置充值套餐配置
     * @return mixed
     * @author ljj
     * @date 2023/6/28 11:00 上午
     */
    public function setConfig()
    {
        $params = (new RechargePackageValidate())->post()->goCheck('setConfig');
        (new RechargePackageLogic())->setConfig($params);
        return $this->success('操作成功',[],1,1);
    }

    /**
     * @notes 充值套餐列表
     * @return mixed
     * @author ljj
     * @date 2023/6/28 11:13 上午
     */
    public function lists()
    {
        return $this->dataLists(new RechargePackageLists());
    }

    /**
     * @notes 添加充值套餐
     * @return mixed
     * @author ljj
     * @date 2023/6/28 11:34 上午
     */
    public function add()
    {
        $params = (new RechargePackageValidate())->post()->goCheck('add');
        (new RechargePackageLogic())->add($params);
        return $this->success('操作成功',[],1,1);
    }

    /**
     * @notes 充值套餐详情
     * @return mixed
     * @author ljj
     * @date 2023/6/28 11:42 上午
     */
    public function detail()
    {
        $params = (new RechargePackageValidate())->get()->goCheck('detail');
        $result = (new RechargePackageLogic())->detail($params);
        return $this->success('',$result);
    }

    /**
     * @notes 编辑充值套餐
     * @return mixed
     * @author ljj
     * @date 2023/6/28 12:08 下午
     */
    public function edit()
    {
        $params = (new RechargePackageValidate())->post()->goCheck('edit');
        (new RechargePackageLogic())->edit($params);
        return $this->success('操作成功',[],1,1);
    }

    /**
     * @notes 删除充值套餐
     * @return mixed
     * @author ljj
     * @date 2023/6/28 12:11 下午
     */
    public function del()
    {
        $params = (new RechargePackageValidate())->post()->goCheck('del');
        (new RechargePackageLogic())->del($params);
        return $this->success('操作成功',[],1,1);
    }

    /**
     * @notes 修改套餐状态
     * @return mixed
     * @author ljj
     * @date 2023/6/28 12:14 下午
     */
    public function status()
    {
        $params = (new RechargePackageValidate())->post()->goCheck('status');
        (new RechargePackageLogic())->status($params);
        return $this->success('操作成功',[],1,1);
    }

    /**
     * @notes 修改推荐状态
     * @return mixed
     * @author ljj
     * @date 2023/6/28 2:25 下午
     */
    public function recommend()
    {
        $params = (new RechargePackageValidate())->post()->goCheck('recommend');
        (new RechargePackageLogic())->recommend($params);
        return $this->success('操作成功',[],1,1);
    }
}