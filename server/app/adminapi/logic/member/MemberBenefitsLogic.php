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

namespace app\adminapi\logic\member;


use app\common\logic\BaseLogic;
use app\common\model\member\MemberBenefits;

class MemberBenefitsLogic extends BaseLogic
{
    /**
     * @notes 添加会员权益
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/6/27 10:50 上午
     */
    public function add($params)
    {
        MemberBenefits::create([
            'name' => $params['name'],
            'image' => $params['image'],
            'status' => $params['status'],
            'sort' => $params['sort'],
            'describe' => $params['describe'] ?? '',
        ]);

        return true;
    }

    /**
     * @notes 编辑会员权益
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/6/27 11:01 上午
     */
    public function edit($params)
    {
        MemberBenefits::update([
            'name' => $params['name'],
            'image' => $params['image'],
            'status' => $params['status'],
            'sort' => $params['sort'],
            'describe' => $params['describe'] ?? '',
        ],['id'=>$params['id']]);

        return true;
    }

    /**
     * @notes 会员权益详情
     * @param $params
     * @return array
     * @author ljj
     * @date 2023/6/27 11:04 上午
     */
    public function detail($params)
    {
        $result = MemberBenefits::where(['id'=>$params['id']])->findOrEmpty()->toArray();

        return $result;
    }

    /**
     * @notes 删除会员权益
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/6/27 11:05 上午
     */
    public function del($params)
    {
        MemberBenefits::destroy($params['id']);

        return true;
    }

    /**
     * @notes 调整状态
     * @param $params
     * @author ljj
     * @date 2023/6/27 11:07 上午
     */
    public function status($params)
    {
        $result = MemberBenefits::findOrEmpty($params['id']);
        $result->status = !$result->status;
        $result->save();
    }
}