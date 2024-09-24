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

namespace app\adminapi\controller\member;


use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\member\MemberPackageLists;
use app\adminapi\logic\member\MemberPackageLogic;
use app\adminapi\validate\member\MemberPackageValidate;

class MemberPackageController extends BaseAdminController
{
    /**
     * @notes 会员套餐列表
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/14 11:46 上午
     */
    public function lists()
    {
        return $this->dataLists(new MemberPackageLists());
    }

    /**
     * @notes 添加会员套餐
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/14 12:16 下午
     */
    public function add()
    {
        $params = (new MemberPackageValidate())->post()->goCheck('add');
        (new MemberPackageLogic())->add($params);
        return $this->success('操作成功',[],1,1);
    }

    /**
     * @notes 会员套餐详情
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/14 2:18 下午
     */
    public function detail()
    {
        $params = (new MemberPackageValidate())->get()->goCheck('detail');
        $result = (new MemberPackageLogic())->detail($params);
        return $this->success('',$result);
    }

    /**
     * @notes 编辑会员套餐
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/14 2:20 下午
     */
    public function edit()
    {
        $params = (new MemberPackageValidate())->post()->goCheck('edit');
        (new MemberPackageLogic())->edit($params);
        return $this->success('操作成功',[],1,1);
    }

    /**
     * @notes 删除会员套餐
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/14 2:23 下午
     */
    public function del()
    {
        $params = (new MemberPackageValidate())->post()->goCheck('del');
        (new MemberPackageLogic())->del($params);
        return $this->success('操作成功',[],1,1);
    }

    /**
     * @notes 调整状态
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/28 4:53 下午
     */
    public function status()
    {
        $params = (new MemberPackageValidate())->post()->goCheck('status');
        (new MemberPackageLogic())->status($params);
        return $this->success('操作成功',[],1,1);
    }

    /**
     * @notes 调整默认套餐
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/28 4:58 下午
     */
    public function default()
    {
        $params = (new MemberPackageValidate())->post()->goCheck('default');
        (new MemberPackageLogic())->default($params);
        return $this->success('操作成功',[],1,1);
    }

    /**
     * @notes 获取配置
     * @return mixed
     * @author ljj
     * @date 2023/5/19 5:14 下午
     */
    public function getConfig()
    {
        $result = (new MemberPackageLogic())->getConfig();
        return $this->success('',$result);
    }

    /**
     * @notes 设置配置
     * @return mixed
     * @author ljj
     * @date 2023/5/19 5:14 下午
     */
    public function setConfig()
    {
        $params = (new MemberPackageValidate())->post()->goCheck('setConfig');
        (new MemberPackageLogic())->setConfig($params);
        return $this->success('操作成功',[],1,1);
    }

    /**
     * @notes 公共列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/6/21 8:21 下午
     */
    public function commonLists()
    {
        $result = (new MemberPackageLogic())->commonLists();
        return $this->success('',$result);
    }

    /**
     * @notes 会员权益列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/6/27 2:31 下午
     */
    public function benefitsLists()
    {
        $result = (new MemberPackageLogic())->benefitsLists();
        return $this->success('',$result);
    }
}