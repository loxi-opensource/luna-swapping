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

namespace app\common\model\member;


use app\common\enum\MemberPackageEnum;
use app\common\model\BaseModel;
use think\model\concern\SoftDelete;

class MemberPackageComment extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';


    /**
     * @notes 评价类型
     * @param $value
     * @param $data
     * @return string|string[]
     * @author ljj
     * @date 2023/4/14 3:12 下午
     */
    public function getTypeDescAttr($value,$data)
    {
        return MemberPackageEnum::getTypeDesc($data['type']);
    }

    /**
     * @notes 评价套餐
     * @param $value
     * @param $data
     * @return mixed
     * @author ljj
     * @date 2023/4/14 3:13 下午
     */
    public function getMemberPackageAttr($value,$data)
    {
        return MemberPackage::where('id',$data['member_package_id'])->value('name');
    }

    /**
     * @notes 评价等级
     * @param $value
     * @param $data
     * @return string
     * @author ljj
     * @date 2023/4/14 3:15 下午
     */
    public function getCommentLevelDescAttr($value,$data)
    {
        $result = '';
        switch ($data['comment_level']) {
            case 5:
            case 4:
                $result = '好评';
                break;
            case 3:
                $result = '中评';
                break;
            case 2:
            case 1:
                $result = '差评';
                break;
        }

        return $result;
    }
}