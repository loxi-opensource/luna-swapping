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

namespace app\api\logic;


use app\common\enum\LunaDrawConst;
use app\common\enum\ShareConst;
use app\common\enum\user\AccountLogEnum;
use app\common\logic\AccountLogLogic;
use app\common\logic\BaseLogic;
use app\common\model\InviteTaskCouple;
use app\common\model\task\TaskInvite;
use app\common\model\task\TaskShare;
use app\common\model\user\User;
use app\common\service\ConfigService;
use app\common\service\FileService;
use app\common\service\wechat\WeChatMnpService;
use TencentCloud\Organization\V20181225\Models\AcceptOrganizationInvitationRequest;
use think\facade\Config;
use think\facade\Db;
use think\helper\Arr;

class ShareLogic extends BaseLogic
{
    /**
     * @notes 任务
     * @param $user_id
     * @return array
     * @author ljj
     * @date 2023/4/19 4:51 下午
     */
    public function task($user_id)
    {
        $result = [];

        $task_share = [
            'status' => ConfigService::get('task_share', 'status', 1),
            'rewards' => ConfigService::get('task_share', 'rewards', 1),
            'max_share' => ConfigService::get('task_share', 'max_share', 5),
        ];
        if ($task_share['status'] == 1) {
            $share_num = TaskShare::where(['user_id' => $user_id])->whereDay('create_time')->where('click_num', '>', 0)->count();
            if ($share_num > $task_share['max_share']) {
                $share_num = $task_share['max_share'];
            }
            $result[] = [
                'image' => FileService::getFileUrl(Config::get('project.default_image.share')),
                'title' => '分享给好友',
                'rewards' => $task_share['rewards'],
                'max' => $task_share['max_share'],
                'num' => $share_num,
                'type' => 1,
            ];
        }

        $task_invite = [
            'status' => ConfigService::get('task_invite', 'status', 1),
            'rewards' => ConfigService::get('task_invite', 'rewards', 1),
            'max_invite' => ConfigService::get('task_invite', 'max_invite', 5),
        ];
        if ($task_invite['status'] == 1) {
            $invite_num = TaskInvite::where(['user_id' => $user_id])->whereDay('create_time')->count();
            if ($invite_num > $task_invite['max_invite']) {
                $invite_num = $task_invite['max_invite'];
            }
            $result[] = [
                'image' => FileService::getFileUrl(Config::get('project.default_image.invite')),
                'title' => '邀请新用户',
                'rewards' => $task_invite['rewards'],
                'max' => $task_invite['max_invite'],
                'num' => $invite_num,
                'type' => 2,
            ];
        }

        return $result;
    }

    /**
     * @notes 分享接口
     * @param $userInfo
     * @return false|int[]
     * @author ljj
     * @date 2023/4/18 4:58 下午
     */
    public function share($userInfo)
    {
        Db::startTrans();
        try {
            $user_id = $userInfo['user_id'] ?? 0;
            if ($user_id) {
                $share_status = ConfigService::get('task_share', 'status', 1);
                $rewards = 0;
                if ($share_status == 1) {
                    $max_share = ConfigService::get('task_share', 'max_share', 5);
                    $share_num = TaskShare::where(['user_id' => $user_id])->whereDay('create_time')->count();
                    if ($max_share > $share_num) {
                        $rewards = ConfigService::get('task_share', 'rewards', 1);
                    } else {
                        $share = TaskShare::where(['user_id' => $user_id])->whereDay('create_time')->order('id', 'desc')->findOrEmpty();
                        return ['share_id' => $share->id ?? 0];
                    }
                }

                //添加分享记录
                //获取用户客户端
                $share = TaskShare::create([
                    'user_id' => $user_id,
                    'channel' => $userInfo['terminal'] ?? 0,
                    'rewards' => $rewards,
                ]);
            }

            // 提交事务
            Db::commit();
            return ['share_id' => $share->id ?? 0];
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }
    }

    /**
     * @notes 点击分享链接
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/4/18 5:13 下午
     */
    public function click($params)
    {
        Db::startTrans();
        try {
            $task_share = TaskShare::findOrEmpty($params['share_id'] ?? 0);
            if (!$task_share->isEmpty()) {
                //第一次点击，增加用户余额
                if ($task_share->click_num == 0 && $task_share->rewards > 0) {
                    User::update(['balance' => ['inc', $task_share->rewards]], ['id' => $task_share->user_id]);
                    // 记录账户流水
                    AccountLogLogic::add($task_share->user_id, AccountLogEnum::UM_INC_SHARE, AccountLogEnum::INC, $task_share->rewards);
                }

                $task_share->click_num = $task_share->click_num + 1;
                $task_share->save();
            }

            // 提交事务
            Db::commit();
            return true;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }
    }

    /**
     * @notes 邀请新用户
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/4/18 5:35 下午
     */
    public function invite($params)
    {
        if ((!isset($params['share_id']) || $params['share_id'] == '') && (!isset($params['user_sn']) || $params['user_sn'] == '')) {
            return true;
        }
        $new_user = User::where(['id' => $params['new_user_id']])->findOrEmpty()->toArray();
//        if ((time() - strtotime($new_user['create_time'])) >= 300) {
//            return true;//当前时间与注册时间差大于5分钟，认定为非新用户
//        }
        if ($new_user['is_new_user'] != 1) {
            return true;//不是非新用户
        }
        $invite = TaskInvite::where(['new_user_id' => $params['new_user_id']])->findOrEmpty();
        if (!$invite->isEmpty()) {
            return true;//已存在邀请记录，认定为非新用户
        }
        if (isset($params['share_id'])) {
            $invite_id = TaskShare::where(['id' => $params['share_id']])->value('user_id');
        } else {
            $invite_id = User::where(['sn' => $params['user_sn']])->value('id');
        }

        if ($invite_id == $params['new_user_id']) {
            return true;//不能成为自己邀请人
        }

        Db::startTrans();
        try {
            $invite_status = ConfigService::get('task_invite', 'status', 1);
            $rewards = 0;
            if ($invite_status == 1) {
                $max_invite = ConfigService::get('task_invite', 'max_invite', 5);
                $invite_num = TaskInvite::where(['user_id' => $invite_id])->whereDay('create_time')->count();
                if ($max_invite > $invite_num) {
                    $rewards = ConfigService::get('task_invite', 'rewards', 1);
                }
            }

            //添加邀请记录
            TaskInvite::create([
                'user_id' => $invite_id,
                'new_user_id' => $params['new_user_id'],
                'task_share_id' => $params['share_id'] ?? 0,
                'rewards' => $rewards,
            ]);

            if (isset($params['share_id'])) {
                //更新分享记录
                TaskShare::update([
                    'invite_num' => ['inc', 1],
                ], ['id' => $params['share_id']]);
            }

            //绑定新用户上级关系
            $first_leader = User::where(['id' => $invite_id])->findOrEmpty()->toArray();
            User::update(['inviter_id' => $invite_id, 'first_leader' => $invite_id, 'second_leader' => $first_leader['first_leader']], ['id' => $params['new_user_id']]);

            //增加用户余额
            if ($rewards > 0) {
                User::update(['balance' => ['inc', $rewards]], ['id' => $invite_id]);
                // 记录账户流水
                AccountLogLogic::add($invite_id, AccountLogEnum::UM_INC_INVITE, AccountLogEnum::INC, $rewards);
            }

            // 提交事务
            Db::commit();
            return true;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }
    }

    /**
     * @notes 获取小程序码
     * @param $params
     * @return bool|string
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @author ljj
     * @date 2023/5/23 11:50 上午
     */
    public function getMnpQrCode($params)
    {
        try {
            if (!isset($params['user_sn']) || $params['user_sn'] == '') {
                throw new \think\Exception('参数缺失');
            }
            if (!isset($params['page']) || $params['page'] == '') {
                throw new \think\Exception('路径缺失');
            }

            $data['page'] = $params['page'];
            $data['scene'] = 'user_sn=' . $params['user_sn'];

            $result = (new WeChatMnpService())->createMpQrCode($data, 'base64');
            return $result;
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }

    // 创建合照任务 /api/share/createCoupleTask
    static function createCoupleTask($params)
    {
        $draft = $params['draft'];
        $tagFileList = Arr::get($draft, 'tagFile');
        if (count($tagFileList) < 1 || count($tagFileList) > 10) {
            self::setError('创建任务失败: 参数错误');
            return false;
        }
        $tagFileIDList = Arr::pluck($tagFileList, 'id');
        $drawNumber = 15;
        if (count($tagFileIDList) > $drawNumber) {
            // 选择风格数量大于作图数量
            $tagFileIDList = Arr::random($tagFileIDList, $drawNumber);
        }
        $tagFileID = implode(',', $tagFileIDList);
//        $fileID = Arr::get($draft, 'userFile.id');
        $userFileID = Arr::get($draft, 'userFile.localFileRes.id');

        $newTask = InviteTaskCouple::create([
            'inviting_user_id' => $params['user_id'],
//            'invited_user_id' => $params['invited_user_id'],
            'tag_file_id' => $tagFileID,
            'inviting_file_id' => $userFileID,
//            'invited_file_id' => $params['invited_file_id'],
            'user_draft' => json_encode($draft, JSON_UNESCAPED_UNICODE),
            'status' => ShareConst::CoupleShareTaskStatusPending,
            'create_time' => time(),
            'update_time' => time(),
            'expire_time' => time() + 60 * 10,
        ]);
        if (!isset($newTask->id)) {
            return false;
        }
        $newTask = InviteTaskCouple::find($newTask->id);
        return isset($newTask->id) ? $newTask->toArray() : false;
    }

    static function coupleTaskDetail($params)
    {
        $task = InviteTaskCouple::with(['invited_file', 'inviting_file'])->find($params['id']);
        isset($task->invited_file->uri) && $task->invited_file->uri = FileService::getFileUrl($task->invited_file->uri);
        isset($task->inviting_file->uri) && $task->inviting_file->uri = FileService::getFileUrl($task->inviting_file->uri);
        return isset($task->id) ? $task->toArray() : false;
    }

    static function joinCoupleTask($params)
    {
        $task = InviteTaskCouple::whereNull('pid')->find($params['id']);
        if (!isset($task->id)) {
            self::setError('父级任务不存在');
            return false;
        }

        // 0=默认 1=进行中 2=已完成 3=已过期
        switch ($task->status) {
            case ShareConst::CoupleShareTaskStatusPending:
                $existConfirmed = InviteTaskCouple::where([
                    'pid' => $task->id,
                    'invited_user_id' => $params['user_id'],
                    'status' => [ShareConst::CoupleShareTaskStatusConfirmed, ShareConst::CoupleShareTaskStatusCompleted],
                ])->find();
                if ($existConfirmed) {
                    return $existConfirmed->toArray();
                }

                $newTask = InviteTaskCouple::create([
                    'pid' => $task->id,
                    'inviting_user_id' => $task->inviting_user_id,
                    'invited_user_id' => $params['user_id'],
                    'tag_file_id' => $task->tag_file_id,
                    'inviting_file_id' => $task->inviting_file_id,
//                    'invited_file_id' => $params['invited_file_id'], // 作图时更新 父子任务
                    'user_draft' => json_encode($task->user_draft, JSON_UNESCAPED_UNICODE),
                    'status' => ShareConst::CoupleShareTaskStatusConfirmed,
                    'create_time' => time(),
                    'update_time' => time(),
                    'expire_time' => time() + 60 * 10,
                ]);
                if (!isset($newTask->id)) {
                    self::setError('加入任务失败-1');
                    return false;
                }

                // 更新父级任务
                $task->invited_user_id = $params['user_id'];
                $task->update_time = time();
                $task->status = ShareConst::CoupleShareTaskStatusConfirmed;
                $task->save();
                return $newTask->toArray();
            case ShareConst::CoupleShareTaskStatusConfirmed:
            case ShareConst::CoupleShareTaskStatusCompleted:
                // 可重入
                $existConfirmed = InviteTaskCouple::where([
                    'pid' => $task->id,
                    'invited_user_id' => $params['user_id'],
                    'status' => [ShareConst::CoupleShareTaskStatusConfirmed, ShareConst::CoupleShareTaskStatusCompleted],
                ])->find();
                if ($existConfirmed) {
                    return $existConfirmed->toArray();
                }
            default:
                self::setError('加入任务失败-2');
                return false;
        }

        self::setError('加入任务失败-3');
        return false;
    }
}
