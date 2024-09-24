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

namespace app\adminapi\validate\setting\upgrade;

use app\adminapi\logic\setting\system\UpgradeLogic;
use app\common\validate\BaseValidate;

/**
 * 系统更新
 * Class UpgradeValidate
 * @package app\adminapi\validate
 */
class UpgradeValidate extends BaseValidate
{

    protected $rule = [
        'id'            => 'require|checkIsAbleUpgrade',
        'update_type'   => 'require|eg:1'
    ];


    protected $message = [
        'id.require'            => '参数缺失',
        'update_type.require'   => '参数缺失',
        'update_type.eg'        => '更新类型错误',
    ];


    /**
     * @notes 检查是否可以更新
     * @param $value
     * @param $reule
     * @param $data
     * @return bool|string
     * @author 段誉
     * @date 2021/8/14 16:22
     */
    protected function checkIsAbleUpgrade($value, $reule, $data)
    {
        //本地更新版本号
        $localVersionData = local_version();
        $localVersionNo = $localVersionData['version'];


        //目标更新版本信息
        $targetVersionData = UpgradeLogic::getVersionDataById($data['id']);

        if (empty($targetVersionData)) {
            return '未获取到对应版本信息';
        }

        //目标更新的版本号
        $targetVersionNo = $targetVersionData['version_no'];


        //本地版本需要小于目标更新版本
        if ($localVersionNo > $targetVersionNo) {
            return '当前系统无法升级到该版本，请逐个版本升级。';
        }

        //获取远程列表
        $remoteData = UpgradeLogic::getRemoteVersion()['lists'] ?? [];
        if (empty($remoteData)) {
            return '获取更新数据失败';
        }

        //是否满足升级
        foreach ($remoteData as $k => $item) {
            if ($item['version_no'] != $localVersionNo) {
                continue;
            }

            if (empty($remoteData[$k - 1])) {
                return '已为最新版本';
            }

            if ($remoteData[$k - 1]['version_no'] != $targetVersionNo) {
                return '当前系统无法升级到该版本，请逐个版本升级。';
            }
        }
        return true;
    }

}