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

namespace app\adminapi\logic\recharge;


use app\common\logic\BaseLogic;
use app\common\model\recharge\RechargePackage;
use app\common\service\ConfigService;

class RechargePackageLogic extends BaseLogic
{
    /**
     * @notes 获取充值套餐配置
     * @return array
     * @author ljj
     * @date 2023/6/28 10:59 上午
     */
    public function getConfig()
    {
        return ['status'=>ConfigService::get('recharge', 'status', 1)];
    }

    /**
     * @notes 设置充值套餐配置
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/6/28 11:00 上午
     */
    public function setConfig($params)
    {
        ConfigService::set('recharge', 'status', $params['status']);

        return true;
    }

    /**
     * @notes 添加充值套餐
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/6/28 11:34 上午
     */
    public function add($params)
    {
        RechargePackage::create([
            'name' => $params['name'],
            'image' => $params['image'],
            'describe' => $params['describe'],
            'sell_price' => $params['sell_price'],
            'sort' => $params['sort'],
            'status' => $params['status'],
            'number' => $params['number'],
            'draw_number' => $params['draw_number'],
            'is_give' => $params['is_give'],
            'give_number' => $params['give_number'],
            'give_draw_number' => $params['give_draw_number'],
        ]);

        return true;
    }

    /**
     * @notes 充值套餐详情
     * @param $params
     * @return array
     * @author ljj
     * @date 2023/6/28 11:42 上午
     */
    public function detail($params)
    {
        $result = RechargePackage::where(['id'=>$params['id']])->findOrEmpty()->toArray();

        return $result;
    }

    /**
     * @notes 编辑充值套餐
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/6/28 12:08 下午
     */
    public function edit($params)
    {
        RechargePackage::update([
            'name' => $params['name'],
            'image' => $params['image'],
            'describe' => $params['describe'],
            'sell_price' => $params['sell_price'],
            'sort' => $params['sort'],
            'status' => $params['status'],
            'number' => $params['number'],
            'draw_number' => $params['draw_number'],
            'is_give' => $params['is_give'],
            'give_number' => $params['give_number'],
            'give_draw_number' => $params['give_draw_number'],
        ],['id'=>$params['id']]);

        return true;
    }

    /**
     * @notes 删除充值套餐
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/6/28 12:11 下午
     */
    public function del($params)
    {
        RechargePackage::destroy($params['id']);

        return true;
    }

    /**
     * @notes 修改套餐状态
     * @param $params
     * @author ljj
     * @date 2023/6/28 12:14 下午
     */
    public function status($params)
    {
        $result = RechargePackage::findOrEmpty($params['id']);
        $result->status = !$result->status;
        $result->save();
    }

    /**
     * @notes 修改推荐状态
     * @param $params
     * @author ljj
     * @date 2023/6/28 2:25 下午
     */
    public function recommend($params)
    {
        $result = RechargePackage::findOrEmpty($params['id']);
        if ($result->is_recommend == 0) {
            RechargePackage::update(['is_recommend'=>0],['is_recommend'=>1]);
        }
        $result->is_recommend = !$result->is_recommend;
        $result->save();
    }
}