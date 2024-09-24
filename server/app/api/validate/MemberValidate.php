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

namespace app\api\validate;


use app\common\model\member\MemberPackage;
use app\common\validate\BaseValidate;

class MemberValidate extends BaseValidate
{
    protected $rule = [
        'member_package_id' => 'require|checkMemberPackageId',
    ];

    protected $message = [
        'member_package_id.require' => '请选择会员套餐',
    ];

    public function sceneBuy()
    {
        return $this->only(['member_package_id']);
    }


    /**
     * @notes 校验会员套餐
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2023/4/20 5:42 下午
     */
    protected function checkMemberPackageId($value, $rule, $data)
    {
        $result = MemberPackage::where('id',$value)->findOrEmpty();
        if ($result->isEmpty()) {
            return '会员套餐错误，请重新选择';
        }
        if ($result->status != 1) {
            return '会员套餐已下架，请重新选择';
        }

        return true;
    }
}
