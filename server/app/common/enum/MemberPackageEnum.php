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

namespace app\common\enum;


class MemberPackageEnum
{
    //评价类型
    const TYPE_INVENTED = 1;//虚拟评价
    const TYPE_USER = 2;//用户自评


    //购买来源
    const CHANNEL_USER = 1;//用户自购
    const CHANNEL_ADMIN = 2;//后台调整
    const CHANNEL_RENEW = 3;//自动/手动续订


    /**
     * @notes 评价类型
     * @param bool $value
     * @return string|string[]
     * @author ljj
     * @date 2023/4/14 3:11 下午
     */
    public static function getTypeDesc($value = true)
    {
        $data = [
            self::TYPE_INVENTED => '虚拟评价',
            self::TYPE_USER => '用户自评'
        ];
        if ($value === true) {
            return $data;
        }
        return $data[$value] ?? '';
    }

    /**
     * @notes 购买来源
     * @param bool $value
     * @return string|string[]
     * @author ljj
     * @date 2023/6/20 8:19 下午
     */
    public static function getChannelDesc($value = true)
    {
        $data = [
            self::CHANNEL_USER => '用户自购',
            self::CHANNEL_ADMIN => '后台调整'
        ];
        if ($value === true) {
            return $data;
        }
        return $data[$value] ?? '';
    }
}
