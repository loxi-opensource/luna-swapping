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
namespace app\adminapi\controller\user;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\user\UserLists;
use app\adminapi\lists\user\UserMemberLists;
use app\adminapi\logic\user\UserLogic;
use app\adminapi\validate\user\AdjustUserDraw;
use app\adminapi\validate\user\AdjustUserMoney;
use app\adminapi\validate\user\UserValidate;

/**
 * 用户控制器
 * Class UserController
 * @package app\adminapi\controller\user
 */
class UserController extends BaseAdminController
{

    /**
     * @notes 用户列表
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/22 16:16
     */
    public function lists()
    {
        return $this->dataLists(new UserLists());
    }


    /**
     * @notes 获取用户详情
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/22 16:34
     */
    public function detail()
    {
        $params = (new UserValidate())->goCheck('detail');
        $detail = UserLogic::detail($params['id']);
        return $this->success('', $detail);
    }


    /**
     * @notes 编辑用户信息
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/22 16:34
     */
    public function edit()
    {
        $params = (new UserValidate())->post()->goCheck('edit');
        UserLogic::setUserInfo($params);
        return $this->success('操作成功', [], 1, 1);
    }


    /**
     * @notes 调整用户余额
     * @return \think\response\Json
     * @author 段誉
     * @date 2023/2/23 14:33
     */
    public function adjustMoney()
    {
        $params = (new AdjustUserMoney())->post()->goCheck();
        $res = UserLogic::adjustUserMoney($params);
        if (true === $res) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail($res);
    }

    /**
     * @notes 调整会员到期时间
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/14 4:12 下午
     */
    public function adjustMember()
    {
        $params = (new UserValidate())->post()->goCheck('adjustMember',['admin_id'=>$this->adminId]);
        $result = UserLogic::adjustMember($params);
        if (true !== $result) {
            return $this->fail($result);
        }
        return $this->success('操作成功', [], 1, 1);
    }

    /**
     * @notes 用户黑名单
     * @return mixed
     * @author cjhao
     * @date 2023/5/26 10:38
     */
    public function blacklist()
    {
        $params = (new UserValidate())->post()->goCheck('blacklist');
        UserLogic::blacklist($params);
        return $this->success('操作成功', [], 1, 1);
    }

    /**
     * @notes 会员开通记录
     * @return mixed
     * @author ljj
     * @date 2023/6/21 10:43 上午
     */
    public function userMember()
    {
        return $this->dataLists(new UserMemberLists());
    }


    /**
     * @notes 调整用户绘画次数
     * @return \think\response\Json
     * @author 段誉
     * @date 2023/2/23 14:33
     */
    public function adjustUserDraw()
    {
        $params = (new AdjustUserDraw())->post()->goCheck();
        $res = UserLogic::adjustUserDraw($params);
        if (true === $res) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail($res);
    }

}
