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

namespace app\common\command;

use app\common\model\auth\Admin;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\facade\Config;

/**
 * 修改超级管理员密码
 */
class Password extends Command
{

    protected function configure()
    {
        $this->setName('password')
            ->addArgument('password', Argument::OPTIONAL, "please input new password")
            ->setDescription('修改超级管理员密码');
    }

    protected function execute(Input $input, Output $output)
    {
        $password = trim($input->getArgument('password'));
        if (empty($password)) {
            $output->error('请输入密码');
            return;
        }
        $passwordSalt = Config::get('project.unique_identification');
        $newPassword = create_password($password, $passwordSalt);
        $rootAdmin = Admin::where('root', 1)->findOrEmpty();
        if ($rootAdmin->isEmpty()) {
            $output->error('超级管理员不存在');
            return;
        }
        $rootAdmin->password = $newPassword;
        $rootAdmin->save();
        $output->info('超级管理修改密码成功！');
        $output->info('账号：' . $rootAdmin->account);
        $output->info('密码：' . $password);
    }
}