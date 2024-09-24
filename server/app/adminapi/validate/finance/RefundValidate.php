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

namespace app\adminapi\validate\finance;


use app\common\enum\PayEnum;
use app\common\enum\RefundEnum;
use app\common\enum\YesNoEnum;
use app\common\model\recharge\RechargeOrder;
use app\common\model\refund\RefundRecord;
use app\common\model\user\User;
use app\common\validate\BaseValidate;


class RefundValidate extends BaseValidate
{
    protected $rule = [
        'record_id' => 'require|checkRecord',
    ];

    protected $message = [
        'record_id.require' => '参数缺失',
    ];


    public function sceneAgain()
    {
        return $this->only(['record_id']);
    }


    /**
     * @notes 校验退款记录
     * @param $recordId
     * @param $rule
     * @param $data
     * @return bool|string
     * @author 段誉
     * @date 2023/3/1 9:40
     */
    protected function checkRecord($recordId, $rule, $data)
    {
        $record = RefundRecord::findOrEmpty($recordId);
        if ($record->isEmpty()) {
            return '退款记录不存在';
        }

        if($record['refund_status'] == RefundEnum::REFUND_SUCCESS) {
            return '该退款记录已退款成功';
        }

        if ($record['refund_status'] != RefundEnum::REFUND_ERROR) {
            return '退款失败才可以重新退款';
        }

        return true;
    }

}