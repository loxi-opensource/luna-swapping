<?php
// +----------------------------------------------------------------------
// | likeshop100%开源免费商用商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshopTeam
// +----------------------------------------------------------------------

namespace app\adminapi\lists\setting\system;


use app\adminapi\lists\BaseAdminDataLists;
use app\adminapi\logic\setting\system\UpgradeLogic;

/**
 * 系统更新列表
 * Class UpgradeLists
 * @package app\adminapi\lists\settings\system
 */
class UpgradeLists extends BaseAdminDataLists
{
    /**
     * @notes 查看系统更新列表
     * @return array
     * @author 段誉
     * @date 2021/8/14 17:16
     */
    public function lists(): array
    {
        $lists = UpgradeLogic::getRemoteVersion($this->pageNo, $this->pageSize)['lists'] ?? [];
        if (empty($lists)) {
            return $lists;
        }
        return UpgradeLogic::formatLists($lists, $this->pageNo);
    }


    /**
     * @notes 查看系统更新列表总数
     * @return int
     * @author 段誉
     * @date 2021/8/14 17:15
     */
    public function count(): int
    {
        $result = UpgradeLogic::getRemoteVersion($this->limitOffset, $this->limitLength);
        return $result['count'] ?? 0;
    }
}