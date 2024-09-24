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

namespace app\api\controller;


use app\api\logic\ShareLogic;
use app\api\validate\ShareValidate;
use app\common\service\ConfigService;

class ShareController extends BaseApiController
{
    public array $notNeedLogin = ['task', 'share', 'click', 'contentList', 'coupleTaskDetail'];

    public function contentList()
    {
        $list = ConfigService::getFromCache('share', 'default');
        return $this->success('Success', compact('list'));
    }

    /**
     * @notes 任务
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/19 4:51 下午
     */
    public function task()
    {
        $result = (new ShareLogic())->task($this->userId);
        return $this->success('', $result);
    }

    /**
     * @notes 分享接口
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/18 4:59 下午
     */
    public function share()
    {
        $result = (new ShareLogic())->share($this->userInfo);
        if (false === $result) {
            return $this->fail(ShareLogic::getError());
        }
        return $this->success('', $result);
    }

    /**
     * @notes 点击分享链接
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/18 5:14 下午
     */
    public function click()
    {
        $params = $this->request->post();
        $result = (new ShareLogic())->click($params);
        if (false === $result) {
            return $this->fail(ShareLogic::getError());
        }
        return $this->success('操作成功');
    }

    /**
     * @notes 邀请新用户
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/18 5:35 下午
     */
    public function invite()
    {
        $params = $this->request->post();
        $params['new_user_id'] = $this->userId;
        $result = (new ShareLogic())->invite($params);
        if (false === $result) {
            return $this->fail(ShareLogic::getError());
        }
        return $this->success('操作成功');
    }


    /**
     * @notes 获取小程序码
     * @return \think\response\Json
     * @author ljj
     * @date 2023/5/23 11:25 上午
     */
    public function getMnpQrCode()
    {
        $params = $this->request->post();
        $result = (new ShareLogic())->getMnpQrCode($params);
        if (false === $result) {
            return $this->fail(ShareLogic::getError());
        }
        return $this->success('获取成功', ['result' => $result]);
    }

    // todo 双人玩法 - 高级版
    public function createCoupleTask()
    {
        $params = (new ShareValidate())->post()->goCheck('createCoupleTask', [
            'user_id' => $this->userId,
        ]);
        $result = ShareLogic::createCoupleTask($params);
        if (false === $result) {
            return $this->fail(ShareLogic::getError());
        }
        return $this->success('Success', $result);
    }

    public function coupleTaskDetail()
    {
        $params = (new ShareValidate())->get()->goCheck('coupleTaskDetail');
        $result = ShareLogic::coupleTaskDetail($params);
        if (false === $result) {
            return $this->fail(ShareLogic::getError());
        }
        return $this->success('Success', $result);
    }

    public function joinCoupleTask()
    {
        $params = (new ShareValidate())->get()->goCheck('joinCoupleTask', [
            'user_id' => $this->userId,
        ]);
        $result = ShareLogic::joinCoupleTask($params);
        if (false === $result) {
            return $this->fail(ShareLogic::getError());
        }
        return $this->success('Success', $result);
    }

}
