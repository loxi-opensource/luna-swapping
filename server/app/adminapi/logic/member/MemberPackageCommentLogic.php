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
use app\common\model\member\MemberPackageComment;

class MemberPackageCommentLogic extends BaseLogic
{
    /**
     * @notes 添加虚拟评价
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/4/14 3:04 下午
     */
    public function add($params)
    {
        MemberPackageComment::create([
            'member_package_id' => $params['member_package_id'],
            'image' => $params['image'],
            'name' => $params['name'],
            'comment_content' => $params['comment_content'],
            'comment_level' => $params['comment_level'],
            'status' => $params['status'],
        ]);

        return true;
    }

    /**
     * @notes 删除评价
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/4/14 3:20 下午
     */
    public function del($params)
    {
        MemberPackageComment::destroy($params['id']);

        return true;
    }
}