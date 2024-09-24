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


use app\api\lists\MemberPackageCommentLists;
use app\api\logic\MemberLogic;
use app\api\validate\MemberValidate;
use app\common\enum\PayEnum;
use app\common\model\member\MemberOrder;

class MemberController extends BaseApiController
{
    /**
     * @notes 会员套餐列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/4/20 5:11 下午
     */
    public function lists()
    {
        $rows = (new MemberLogic())->lists();
        $rows = array_map(function ($item) {
           if ($item['could_try']) {
               $item['could_try'] = MemberLogic::userCouldTry($this->userId) ? 1 : 0;
           }
            return $item;
        }, $rows);

        return $this->success('Success', $rows);
    }

    /**
     * @notes 会员套餐评价列表
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/20 5:20 下午
     */
    public function commentLists()
    {
        return $this->dataLists(new MemberPackageCommentLists());
    }

    /**
     * @notes 购买
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/20 5:51 下午
     */
    public function buy()
    {
        $params = (new MemberValidate())->post()->goCheck('buy', ['user_id' => $this->userId, 'terminal' => $this->userInfo['terminal']]);
        $result = (new MemberLogic())->buy($params);
        if (false === $result) {
            return $this->fail(MemberLogic::getError());
        }
        return $this->success('', $result);
    }

    /**
     * @notes 最近三十条购买记录
     * @return \think\response\Json
     * @author ljj
     * @date 2023/4/25 7:32 下午
     */
    public function buyLog()
    {
        $result = (new MemberLogic())->buyLog();
        return $this->success('', $result);
    }

}
