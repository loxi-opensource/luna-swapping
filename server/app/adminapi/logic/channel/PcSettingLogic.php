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
namespace app\adminapi\logic\channel;
use app\common\service\ConfigService;

/**
 * pc端设置逻辑类
 * Class PcSettingLogic
 * @package app\adminapi\logic\channel
 */
class PcSettingLogic
{

    /**
     * @notes 获取pc端设置
     * @author cjhao
     * @return array
     * @date 2023/4/19 15:26
     */
    public function getConfig()
    {
        $config = [
            // 渠道状态 0-关闭 1-开启
            'status' => ConfigService::get('pc_page', 'status', 1),
            // 关闭后渠道后访问页面 0-空页面 1-自定义链接
            'page_status' => ConfigService::get('pc_page', 'page_status', 0),
            // 自定义链接
            'page_url' => ConfigService::get('pc_page', 'page_url', ''),
            'url' => request()->domain() . '/pc',
        ];

        return $config;

    }


    /**
     * @notes 设置pc端设置
     * @param array $params
     * @author cjhao
     * @date 2023/4/19 15:27
     */
    public function setConfig(array $params)
    {
        ConfigService::set('pc_page', 'status', $params['status']);
        ConfigService::set('pc_page', 'page_status', $params['page_status']);
        ConfigService::set('pc_page', 'page_url', $params['page_url']);


//        // 恢复原入口
//        if(file_exists('./pc/index_lock.html')) {
//            // 存在则原商城入口被修改过，先清除修改后的入口
//            unlink('./pc/index.html');
//            // 恢复原入口
//            rename('./pc/index_lock.html', './pc/index.html');
//        }
//
//        // H5商城关闭 且 显示空白页
//        if($params['status'] == 0) {
//            // 变更文件名
//            rename('./pc/index.html', './pc/index_lock.html');
//            // 创建新空白文件
//            $newfile = fopen('./pc/index.html', 'w');
//            fclose($newfile);
//        }
    }

}