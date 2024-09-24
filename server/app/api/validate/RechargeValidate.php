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

namespace app\api\validate;

use app\api\logic\UserLogic;
use app\common\enum\PayEnum;
use app\common\model\recharge\RechargeOrder;
use app\common\model\recharge\RechargePackage;
use app\common\service\JsonService;
use app\common\validate\BaseValidate;

/**
 * 用户验证器
 * Class UserValidate
 * @package app\shopapi\validate
 */
class RechargeValidate extends BaseValidate
{

    protected $rule = [
        'recharge_package_id' => 'require|checkRechargePackageId',
        'take_discount' => 'in:0,1',
        'category' => 'require|in:gallary,portrait'
    ];

    protected $message = [
        'recharge_package_id.require' => '请选择充值套餐',
    ];


    public function sceneRecharge()
    {
        return $this->only(['recharge_package_id', 'take_discount']);
    }

    public function sceneRechargePackage()
    {
        return $this->only(['category']);
    }

    public function scenecheckUserAllowFreeTrial()
    {
        return $this->only(['category']);
    }

    /**
     * @notes 校验充值套餐
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2023/4/19 2:31 下午
     */
    protected function checkRechargePackageId($value, $rule, $data)
    {
        $result = RechargePackage::where('id', $value)->findOrEmpty();
        if ($result->isEmpty()) {
            return '充值套餐错误，请重新选择';
        }
        if ($result->status != 1) {
            return '充值套餐已下架，请重新选择';
        }

        if (floatval($result->sell_price) == 0) {
            switch ($result->category) {
                default:
                    // 国内的套餐：判断是否可以免费体验
                    if (!RechargePackage::isTerminalAllowFreeTry($this->request->userInfo['terminal'])) {
                        JsonService::throw('Terminal not allow free trial');
                    } else {
                        // 校验免费体验额度 TODO 每天最多免费体验3次
                        $consumeCnt = RechargeOrder::where([
                            'user_id' => $this->request->userId,
                            'recharge_package_id' => $value,
                            'pay_status' => PayEnum::ISPAID
                        ])
                            ->where('create_time', '>=', strtotime('today 0:00'))
                            ->count();
                        if ($consumeCnt >= RechargePackage::UserFreeTryTimesLimitPerDay) {
                            if (!UserLogic::isinternalTestingMember($this->request->userId)) {
                                JsonService::throw('No more free trials');
                            }
                        }
                    }
                    break;
            }
        }

        return true;
    }

}
