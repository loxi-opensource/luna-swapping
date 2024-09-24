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

namespace app\adminapi\logic\channel;

use app\api\service\UserTokenService;
use app\common\enum\OfficialAccountEnum;
use app\common\enum\user\UserTerminalEnum;
use app\common\enum\YesNoEnum;
use app\common\logic\BaseLogic;
use app\common\model\channel\OfficialAccountReply;
use app\common\model\user\User;
use app\common\service\wechat\WeChatOaService;


/**
 * 微信公众号回复逻辑层
 * Class OfficialAccountReplyLogic
 * @package app\adminapi\logic\channel
 */
class OfficialAccountReplyLogic extends BaseLogic
{
    /**
     * @notes 添加回复(关注/关键词/默认)
     * @param $params
     * @return bool
     * @author 段誉
     * @date 2022/3/29 10:57
     */
    public static function add($params)
    {
        try {
            // 关键字回复排序值须大于0
            if ($params['reply_type'] == OfficialAccountEnum::REPLY_TYPE_KEYWORD && $params['sort'] <= 0) {
                throw new \Exception('排序值须大于0');
            }
            if ($params['reply_type'] != OfficialAccountEnum::REPLY_TYPE_KEYWORD && $params['status']) {
                // 非关键词回复只能有一条记录处于启用状态，所以将该回复类型下的已有记录置为禁用状态
                OfficialAccountReply::where(['reply_type' => $params['reply_type']])->update(['status' => YesNoEnum::NO]);
            }

            //图文类型的内容，上传图片到微信
            if ($params['content_type'] == OfficialAccountEnum::CONTENT_TYPE_IMAGE_TEXT) {
                $uploadImage = (new WeChatOaService())->uploadImage($params['content_image']);
                $params['content_image_media_id'] = $uploadImage['media_id'] ?? '';
            }

            OfficialAccountReply::create($params);
            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 查看回复详情
     * @param $params
     * @return array
     * @author 段誉
     * @date 2022/3/29 11:00
     */
    public static function detail($params)
    {
        $field = 'id,name,keyword,reply_type,matching_type,content_type,content,status,sort,content_image';
        $field .= ',reply_type as reply_type_desc, matching_type as matching_type_desc, content_type as content_type_desc, status as status_desc';
        return OfficialAccountReply::field($field)->findOrEmpty($params['id'])->toArray();
    }


    /**
     * @notes 编辑回复(关注/关键词/默认)
     * @param $params
     * @return bool
     * @author 段誉
     * @date 2022/3/29 11:01
     */
    public static function edit($params)
    {
        try {
            // 关键字回复排序值须大于0
            if ($params['reply_type'] == OfficialAccountEnum::REPLY_TYPE_KEYWORD && $params['sort'] <= 0) {
                throw new \Exception('排序值须大于0');
            }
            if ($params['reply_type'] != OfficialAccountEnum::REPLY_TYPE_KEYWORD && $params['status']) {
                // 非关键词回复只能有一条记录处于启用状态，所以将该回复类型下的已有记录置为禁用状态
                OfficialAccountReply::where(['reply_type' => $params['reply_type']])->update(['status' => YesNoEnum::NO]);
            }

            //图文类型的内容，上传图片到微信
            if ($params['content_type'] == OfficialAccountEnum::CONTENT_TYPE_IMAGE_TEXT) {
                $uploadImage = (new WeChatOaService())->uploadImage($params['content_image']);
                $params['content_image_media_id'] = $uploadImage['media_id'] ?? '';
            }

            OfficialAccountReply::update($params);
            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 删除回复(关注/关键词/默认)
     * @param $params
     * @author 段誉
     * @date 2022/3/29 11:01
     */
    public static function delete($params)
    {
        OfficialAccountReply::destroy($params['id']);
    }


    /**
     * @notes 更新排序
     * @param $params
     * @author 段誉
     * @date 2022/3/29 11:01
     */
    public static function sort($params)
    {
        $params['sort'] = $params['new_sort'];
        OfficialAccountReply::update($params);
    }


    /**
     * @notes 更新状态
     * @param $params
     * @author 段誉
     * @date 2022/3/29 11:01
     */
    public static function status($params)
    {
        $reply = OfficialAccountReply::findOrEmpty($params['id']);

        //更改同类型的状态
        if ($reply->status == 0) {
            OfficialAccountReply::update(['status'=>0],['reply_type'=>$reply->reply_type]);
        }

        $reply->status = !$reply->status;
        $reply->save();
    }


    /**
     * @notes 微信公众号回调
     * @return \Psr\Http\Message\ResponseInterface|void
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \ReflectionException
     * @throws \Throwable
     * @author 段誉
     * @date 2023/2/27 14:38\
     */
    public static function index()
    {
        $server = (new WeChatOaService())->getServer();

        // 事件
        $server->addMessageListener(OfficialAccountEnum::MSG_TYPE_EVENT, function ($message, \Closure $next) {
            // 二维码所带key(用户登录状态信息缓存key) - 用于标记用户是否已扫码。是否已登录
            $event_key = self::parseFrom($message['EventKey'] ?? '');
            // 扫码登录
            if ($message['Event'] == 'SCAN' && !empty($event_key)) {
                $content = self::scanLoginContent($event_key,$message['FromUserName']);
                return $content;
            }

            switch ($message['Event']) {
                case OfficialAccountEnum::EVENT_SUBSCRIBE: // 关注事件

                    // 扫码登录
                    if (!empty($event_key)) {
                        $content = self::scanLoginContent($event_key,$message['FromUserName']);
                        return $content;
                    }

                    //关注回复
                    $reply = OfficialAccountReply::where(['reply_type' => OfficialAccountEnum::REPLY_TYPE_FOLLOW, 'status' => YesNoEnum::YES])->findOrEmpty()->toArray();
                    $replyContent = '';

                    if (!empty($reply)) {
                        //文本内容
                        if ($reply['content_type'] == OfficialAccountEnum::CONTENT_TYPE_TEXT) {
                            $replyContent = $reply['content'];
                        }
                        //图文内容-发送多条消息
                        if ($reply['content_type'] == OfficialAccountEnum::CONTENT_TYPE_IMAGE_TEXT) {
                            //调用客服消息接口-发送文本
                            $WeChatOaService = new WeChatOaService();
                            $send_data = [
                                'touser' => $message['FromUserName'],
                                'msgtype' => 'text',
                                'text' => [
                                    'content' => $reply['content']
                                ],
                            ];
                            $WeChatOaService->customSend($send_data);
                            //调用客服消息接口-发送图片
                            $send_data = [
                                'touser' => $message['FromUserName'],
                                'msgtype' => 'image',
                                'image' => [
                                    'media_id' => $reply['content_image_media_id']
                                ],
                            ];
                            $WeChatOaService->customSend($send_data);
                        }
                        //超链接内容
                        if ($reply['content_type'] == OfficialAccountEnum::CONTENT_TYPE_LINK) {
                            $replyContent = "<a href='" . $reply['content'] . "'>".$reply['content']."</a>";
                        }
                    }

                    if (empty($reply)) {
                        // 未启用关注回复 或 关注回复内容为空
                        $replyContent = static::getDefaultReply();
                    }
                    if ($replyContent) {
                        return $replyContent;
                    }
                    break;
            }
            return $next($message);
        });

        // 文本
        $server->addMessageListener(OfficialAccountEnum::MSG_TYPE_TEXT, function ($message, \Closure $next) {
            $replyList = OfficialAccountReply::where([
                'reply_type' => OfficialAccountEnum::REPLY_TYPE_KEYWORD,
                'status' => YesNoEnum::YES
            ])
                ->order('sort asc')
                ->select();

            $replyContent = '';
            foreach ($replyList as $reply) {
                switch ($reply['matching_type']) {
                    case OfficialAccountEnum::MATCHING_TYPE_FULL:
                        $reply['keyword'] === $message['Content'] && $replyContent = $reply['content'];
                        break;
                    case OfficialAccountEnum::MATCHING_TYPE_FUZZY:
                        stripos($message['Content'], $reply['keyword']) !== false && $replyContent = $reply['content'];
                        break;
                }
                if ($replyContent) {
                    break; // 得到回复文本，中止循环
                }
            }
            //消息回复为空的话，找默认回复
            if (empty($replyContent)) {
                $replyContent = static::getDefaultReply();
            }
            if ($replyContent) {
                return $replyContent;
            }
            return $next($message);
        });

        return $server->serve();
    }


    /**
     * @notes 默认回复信息
     * @return mixed
     * @author 段誉
     * @date 2023/2/27 14:36
     */
    public static function getDefaultReply()
    {
        return OfficialAccountReply::where([
            'reply_type' => OfficialAccountEnum::REPLY_TYPE_DEFAULT,
            'status' => YesNoEnum::YES
        ])
            ->value('content');
    }

    /**
     * @notes 解析微信事件key,用于获取key值
     * @param $event_key
     * @return mixed|string
     * @author 段誉
     * @date 2021/10/21 15:09
     */
    public static function parseFrom($event_key)
    {
        //剪切掉关注事件携带的前缀
        if (false !== stripos($event_key, 'qrscene_')) {
            $event_key = substr($event_key, 8);
        }
        return $event_key;
    }


    /**
     * @notes 扫码登录事件-返回登录消息
     * @param $event_key
     * @return string
     * @author 段誉
     * @date 2022/7/25 15:26
     */
    public static function scanLoginContent($event_key,$openid)
    {
        // 校验用所扫的二维码是否已失效(状态为已登录即失效)
        $is_login = self::checkIsLogin($event_key);
        if (true === $is_login) {
            return "二维码已失效，请重新扫码";
        }

        // 设置为已扫码-用于前端判断用户是否已扫码
        self::setIsScan($event_key);

        //判断用户是否已授权
        $user = User::alias('u')
            ->field('u.id,u.sn,u.mobile,u.nickname,u.avatar,u.mobile,u.is_disable,u.is_new_user')
            ->join('user_auth au', 'au.user_id = u.id')
            ->where(function ($query) use ($openid) {
                $query->whereOr(['au.openid' => $openid]);
            })
            ->findOrEmpty();
        if (!$user->isEmpty()) {
            //更新登录信息
            $user->login_time = time();
            $user->login_ip = request()->ip();
            $user->save();

            // 设置为已登录状态
            self::setIsLogin($event_key,$user->id);

            return "登录成功";
        } else {
            $domain = request()->domain();
            if (!empty(config('project.test_web_domain'))) {
                $domain = config('project.test_web_domain');
            }
            $url = $domain . "/mobile/pages/pc_login/pc_login?key=" . $event_key.'&is_pc=1';
            return "<a href='" . $url . "'>点击确认登录</a>";
        }
    }


    /**
     * @notes 是否已登录
     * @param $key
     * @return bool
     * @author 段誉
     * @date 2022/6/1 15:10
     */
    public static function checkIsLogin($key)
    {
        if (empty($key)) {
            return false;
        }

        $data = cache($key);
        if ($data['is_login']) {
            return true;
        }
        return false;
    }


    /**
     * @notes 设置为已扫码
     * @param $key
     * @return bool
     * @author 段誉
     * @date 2022/6/1 15:10
     */
    public static function setIsScan($key)
    {
        if (empty($key)) {
            return false;
        }

        $oldData = cache($key);
        //重置缓存信息
        $data = [
            'token' => $oldData['token'] ?? [],
            "key" => $key,
            "status" => 0, // 登录状态
            "session_id" => $oldData['session_id'],
            "salt" => $oldData['salt'],
            "create_time" => time(),
            "ip" => $oldData['ip'],
            'is_scan' => 1, // 扫码状态
            'is_login' => $oldData['is_login'], // 是否已获取到token
            'invite_code' => $oldData['invite_code'] ?? '', // 邀请码
        ];
        cache($key, $data, 120);
        return true;
    }


    /**
     * @notes 设置为已登录状态
     * @param $key
     * @param $user_id
     * @return bool
     * @author 段誉
     * @date 2022/6/1 15:10
     */
    public static function setIsLogin($key, $user_id)
    {
        if (empty($key)) {
            return false;
        }

        $oldData = cache($key);
        //重置缓存信息
        $data = [
            'token' => UserTokenService::setToken($user_id, UserTerminalEnum::PC),
            "key" => $key,
            "status" => 1, // 登录状态
            "session_id" => $oldData['session_id'],
            "salt" => $oldData['salt'],
            "create_time" => time(),
            "ip" => $oldData['ip'],
            'is_scan' => 1, // 扫码状态
            'is_login' => 1, // 是否已获取到token
            'invite_code' => $oldData['invite_code'] ?? ''
        ];
        cache($key, $data, 120);
        return true;
    }
}