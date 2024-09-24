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


use app\common\validate\BaseValidate;

class FeedbackValidate extends BaseValidate
{
    protected $rule = [
        'type' => 'require|in:1,2,3',
        'content' => 'require',
        'mobile' => 'require',
        'images' => 'array|length:0,5',
        'task_id' => 'require|integer',
        'image_position' => 'require|integer',
        'satisfaction' => 'require|in:Sad,Normal,Excellent',
    ];

    protected $message = [
        'type.require' => '请选择反馈类型',
        'type.in' => '反馈类型值错误',
        'content.require' => '请输入反馈内容',
        'mobile.require' => '请输入联系方式',
//        'mobile.mobile' => '请输入正确的联系方式',
    ];

    public function sceneAdd()
    {
        return $this->only(['type', 'content', 'mobile', 'images']);
    }

    public function sceneaddSatisfaction()
    {
        return $this->only(['task_id', 'image_position', 'satisfaction']);
    }
}
