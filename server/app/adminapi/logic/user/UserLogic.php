<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------
namespace app\adminapi\logic\user;

use app\common\enum\MemberPackageEnum;
use app\common\enum\PayEnum;
use app\common\enum\user\AccountLogEnum;
use app\common\logic\AccountLogLogic;
use app\common\logic\BaseLogic;
use app\common\model\member\MemberPackage;
use app\common\model\user\User;
use app\common\model\user\UserMember;
use think\facade\Db;

/**
 * 用户逻辑层
 * Class UserLogic
 * @package app\adminapi\logic\user
 */
class UserLogic extends BaseLogic
{

    /**
     * @notes 用户详情
     * @param int $userId
     * @return array
     * @author 段誉
     * @date 2022/9/22 16:32
     */
    public static function detail(int $userId): array
    {
        $user = User::where(['id' => $userId])
            ->withoutField('password,update_time,delete_time')
            ->append(['channel_desc','inviter_name','invite_num','member_info'])
            ->findOrEmpty()
            ->toArray();

        //会员
        $user['member_desc'] = $user['member_info']['member_name'];
        $user['member_end_time_desc'] = $user['member_info']['member_end_time'];
        $user['is_member'] = $user['member_info']['is_member'];
        $user['member_package_id'] = $user['member_info']['member_package_id'];

        //分销
        $user['is_distribution_desc'] = '未开通';
        if ($user['is_distribution']) {
            $user['is_distribution_desc'] = '已开通';
        }

        return $user;
    }


    /**
     * @notes 更新用户信息
     * @param array $params
     * @return User
     * @author 段誉
     * @date 2022/9/22 16:38
     */
    public static function setUserInfo(array $params)
    {
        return User::update([
            'id' => $params['id'],
            $params['field'] => $params['value']
        ]);
    }


    /**
     * @notes 调整用户余额
     * @param array $params
     * @return bool|string
     * @author 段誉
     * @date 2023/2/23 14:25
     */
    public static function adjustUserMoney(array $params)
    {
        Db::startTrans();
        try {
            $user = User::find($params['user_id']);
            if (AccountLogEnum::INC == $params['action']) {
                //调整余额
                $user->balance += $params['num'];
                $user->save();
                //记录日志
                AccountLogLogic::add(
                    $user->id,
                    AccountLogEnum::UM_INC_ADMIN,
                    AccountLogEnum::INC,
                    $params['num'],
                    '',
                    $params['remark'] ?? ''
                );
            } else {
                $user->balance -= $params['num'];
                $user->save();
                //记录日志
                AccountLogLogic::add(
                    $user->id,
                    AccountLogEnum::UM_DEC_ADMIN,
                    AccountLogEnum::DEC,
                    $params['num'],
                    '',
                    $params['remark'] ?? ''
                );
            }

            Db::commit();
            return true;

        } catch (\Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
    }

    /**
     * @notes 调整会员到期时间
     * @param array $params
     * @return bool
     * @author ljj
     * @date 2023/4/14 4:11 下午
     */
    public static function adjustMember(array $params)
    {
        if ($params['member_perpetual']) {
            User::update([
                'id' => $params['id'],
                'member_perpetual' => 1
            ]);
        } else {
            User::update([
                'id' => $params['id'],
                'member_end_time' => empty($params['member_end_time']) ? null : strtotime($params['member_end_time']),
                'member_perpetual' => 0
            ]);
        }

        if ((isset($params['member_end_time']) && !empty($params['member_end_time'])) || $params['member_perpetual']) {
            if (!isset($params['member_package_id']) || empty($params['member_package_id'])) {
                return '请选择会员套餐';
            }
        }

        //添加会员开通记录
        $member_package = MemberPackage::where(['id'=>$params['member_package_id'] ?? 0])->findOrEmpty()->toArray();
        UserMember::create([
            'user_id' => $params['id'],
            'operate_id' => $params['admin_id'],
            'channel' => MemberPackageEnum::CHANNEL_ADMIN,
            'package_name' => $member_package['name'] ?? '',
            'member_end_time' => (isset($params['member_package_id']) && !empty($params['member_package_id'])) ? ((isset($params['member_end_time']) && $params['member_end_time'] != '') ? strtotime($params['member_end_time']) : null) : null,
            'is_perpetual' => $params['member_perpetual'],
            'package_info' => json_encode($member_package,JSON_UNESCAPED_UNICODE),
            'add_member_time' => (isset($params['member_end_time']) && $params['member_end_time'] != '') ? (strtotime($params['member_end_time']) - time()) : 0,
        ]);

        return true;
    }


    /**
     * @notes 用户放入黑名单
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2023/5/26 10:40
     */
    public static function blacklist(array $params)
    {
        $user = User::findOrEmpty($params['id']);
        $user->is_blacklist = $user->is_blacklist ? 0 : 1;
        $user->save();
        return true;
    }

    /**
     * @notes 调整绘画次数
     * @param array $params
     * @return bool|string
     * @author 段誉
     * @date 2023/6/28 15:57
     */
    public static function adjustUserDraw(array $params)
    {
        Db::startTrans();
        try {
            $user = User::find($params['user_id']);
            if (AccountLogEnum::INC == $params['action']) {
                //调整余额
                $user->balance_draw += $params['num'];
                $user->save();
                //记录日志
                AccountLogLogic::add(
                    $user->id,
                    AccountLogEnum::DRAW_INC_ADMIN,
                    AccountLogEnum::INC,
                    $params['num'],
                    '',
                    $params['remark'] ?? ''
                );
            } else {
                $user->balance_draw -= $params['num'];
                $user->save();
                //记录日志
                AccountLogLogic::add(
                    $user->id,
                    AccountLogEnum::DRAW_DEC_ADMIN,
                    AccountLogEnum::DEC,
                    $params['num'],
                    '',
                    $params['remark'] ?? ''
                );
            }

            Db::commit();
            return true;

        } catch (\Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
    }
}