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
use app\common\model\member\MemberPackage;
use app\common\service\ConfigService;

class MemberPackageLogic extends BaseLogic
{
    /**
     * @notes 添加会员套餐
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/4/14 12:16 下午
     */
    public function add($params)
    {
        MemberPackage::create([
            'name' => $params['name'],
            'duration' => $params['duration'],
            'sell_price' => $params['sell_price'],
            'lineation_price' => $params['lineation_price'],
            'is_retrieve' => $params['is_retrieve'],
            'retrieve_amount' => $params['retrieve_amount'],
            'sort' => $params['sort'],
            'status' => $params['status'],
            'is_perpetual' => $params['is_perpetual'],
            'chat_limit' => (!isset($params['chat_limit']) || empty($params['chat_limit'])) ? null : $params['chat_limit'],
            'benefits_ids' => empty($params['benefits_ids']) ? '' : implode(',',$params['benefits_ids']),
            'give_draw_number' => (!isset($params['give_draw_number']) || empty($params['give_draw_number'])) ? null : $params['give_draw_number'],
            'give_chat_number' => (!isset($params['give_chat_number']) || empty($params['give_chat_number'])) ? null : $params['give_chat_number'],
        ]);

        return true;
    }

    /**
     * @notes 会员套餐详情
     * @param $params
     * @return array
     * @author ljj
     * @date 2023/4/14 2:18 下午
     */
    public function detail($params)
    {
        $result = MemberPackage::where(['id'=>$params['id']])->findOrEmpty()->toArray();
        $result['benefits_ids'] = empty($result['benefits_ids']) ? [] : explode(',',$result['benefits_ids']);
        foreach ($result['benefits_ids'] as &$benefits_id) {
            $benefits_id = (int)$benefits_id;
        }

        return $result;
    }

    /**
     * @notes 编辑会员套餐
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/4/14 2:20 下午
     */
    public function edit($params)
    {
        MemberPackage::update([
            'name' => $params['name'],
            'duration' => $params['duration'],
            'sell_price' => $params['sell_price'],
            'lineation_price' => $params['lineation_price'],
            'is_retrieve' => $params['is_retrieve'],
            'retrieve_amount' => $params['retrieve_amount'],
            'sort' => $params['sort'],
            'status' => $params['status'],
            'is_perpetual' => $params['is_perpetual'],
            'chat_limit' => (!isset($params['chat_limit']) || empty($params['chat_limit'])) ? null : $params['chat_limit'],
            'benefits_ids' => empty($params['benefits_ids']) ? '' : implode(',',$params['benefits_ids']),
            'give_draw_number' => (!isset($params['give_draw_number']) || empty($params['give_draw_number'])) ? null : $params['give_draw_number'],
            'give_chat_number' => (!isset($params['give_chat_number']) || empty($params['give_chat_number'])) ? null : $params['give_chat_number'],
        ],['id'=>$params['id']]);

        return true;
    }

    /**
     * @notes 删除会员套餐
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/4/14 2:23 下午
     */
    public function del($params)
    {
        MemberPackage::destroy($params['id']);

        return true;
    }

    /**
     * @notes 调整状态
     * @param $params
     * @author ljj
     * @date 2023/4/28 4:53 下午
     */
    public function status($params)
    {
        $result = MemberPackage::findOrEmpty($params['id']);
        $result->status = !$result->status;
        $result->save();
    }

    /**
     * @notes 调整默认套餐
     * @param $params
     * @author ljj
     * @date 2023/4/28 4:58 下午
     */
    public function default($params)
    {
        $result = MemberPackage::findOrEmpty($params['id']);

        if ($result->is_default == 0) {
            MemberPackage::update(['is_default'=>0],['is_default'=>1]);
        }
        $result->is_default = !$result->is_default;
        $result->save();
    }

    /**
     * @notes 获取配置
     * @return array
     * @author ljj
     * @date 2023/5/19 5:13 下午
     */
    public function getConfig()
    {
        $result['status'] = ConfigService::get('member', 'status', 1);

        return $result;
    }

    /**
     * @notes 设置配置
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/5/19 5:13 下午
     */
    public function setConfig($params)
    {
        ConfigService::set('member', 'status', $params['status']);

        return true;
    }


    /**
     * @notes 公共列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/6/21 8:21 下午
     */
    public function commonLists()
    {
        $lists = MemberPackage::field('id,name,duration,is_perpetual,sell_price,sort,status,is_default')
            ->append(['buy_num'])
            ->order(['sort'=>'desc','id'=>'asc'])
            ->select()
            ->toArray();

        return $lists;
    }

    /**
     * @notes 会员权益列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/6/27 2:31 下午
     */
    public function benefitsLists()
    {
        $lists = MemberBenefits::field('id,name,image')
            ->order(['sort'=>'desc','id'=>'desc'])
            ->where(['status'=>1])
            ->select()
            ->toArray();

        return $lists;
    }
}