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

namespace app\adminapi\validate\member;


use app\common\validate\BaseValidate;

class MemberPackageCommentValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require',
        'member_package_id' => 'require',
        'image' => 'require',
        'name' => 'require',
        'comment_content' => 'require',
        'comment_level' => 'require|in:1,2,3,4,5',
        'status' => 'require|in:0,1',
    ];

    protected $message = [
        'id.require' => '参数缺失',
        'member_package_id.require' => '请选择评价套餐',
        'image.require' => '请选择头像',
        'name.require' => '请输入用户昵称',
        'comment_content.require' => '请输入评价内容',
        'comment_level.require' => '请选择评价等级',
        'comment_level.in' => '评价等级错误',
        'status.require' => '请选择状态',
        'status.in' => '状态错误',
    ];


    public function sceneAdd()
    {
        return $this->only(['member_package_id','image','name','comment_content','comment_level','status']);
    }

    public function sceneDel()
    {
        return $this->only(['id']);
    }
}