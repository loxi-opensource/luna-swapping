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

namespace app\adminapi\validate\recharge;

use app\common\validate\BaseValidate;
use think\facade\Validate;

class RechargePackageValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require',
        'name' => 'require',
        'image' => 'require',
        'describe' => 'require',
        'sell_price' => 'require|float|gt:0',
        'sort' => 'number',
        'status' => 'require|in:0,1',
        'number' => 'require|number|egt:0',
        'draw_number' => 'require|number|egt:0',
        'is_give' => 'require|in:0,1|checkGive',
        'give_number' => 'requireIf:is_give,1',
        'give_draw_number' => 'requireIf:is_give,1',
    ];

    protected $message = [
        'id.require' => '参数缺失',
        'name.require' => '请输入套餐名称',
        'image.require' => '请选择封面',
        'describe.require' => '请输入套餐描述',
        'sell_price.require' => '请输入套餐价格',
        'sell_price.float' => '套餐价格值错误',
        'sell_price.gt' => '套餐价格必须大于0',
        'sort.number' => '排序值错误',
        'status.require' => '请选择套餐状态',
        'status.in' => '套餐状态值错误',
        'number.require' => '请输入对话次数',
        'number.number' => '对话次数值错误',
        'number.gt' => '对话次数必须大于0',
        'draw_number.require' => '请输入绘画次数',
        'draw_number.number' => '绘画次数值错误',
        'draw_number.gt' => '绘画次数必须大于0',
        'is_give.require' => '开启赠送值缺失',
        'is_give.in' => '开启赠送值错误',
        'give_number.requireIf' => '请输入赠送对话次数',
        'give_draw_number.requireIf' => '请输入赠送绘画次数',
    ];


    public function sceneSetConfig()
    {
        return $this->only(['status']);
    }

    public function sceneAdd()
    {
        return $this->only(['name','image','describe','sell_price','sort','status','number','draw_number','is_give','give_number','give_draw_number']);
    }

    public function sceneDetail()
    {
        return $this->only(['id']);
    }

    public function sceneEdit()
    {
        return $this->only(['id','name','image','describe','sell_price','sort','status','number','draw_number','is_give','give_number','give_draw_number']);
    }

    public function sceneDel()
    {
        return $this->only(['id']);
    }

    public function sceneStatus()
    {
        return $this->only(['id']);
    }

    public function sceneRecommend()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 校验充值套餐赠送
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2023/6/30 10:57 上午
     */
    public function checkGive($value,$rule,$data)
    {
        if ($value == 1) {
            if (!Validate::number($data['give_number'])) {
                return '赠送对话次数值错误';
            }
            if ($data['give_number'] < 0) {
                return '赠送对话次数必须大于等于0';
            }
            if (!Validate::number($data['give_draw_number'])) {
                return '赠送绘画次数值错误';
            }
            if ($data['give_draw_number'] < 0) {
                return '赠送绘画次数必须大于等于0';
            }
        }
        return true;
    }

    /**
     * @notes 删除套餐验证
     * @param $value
     * @return bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2023/7/12 11:46
     */
    public function checkDel($value)
    {
        return true;
    }
}
