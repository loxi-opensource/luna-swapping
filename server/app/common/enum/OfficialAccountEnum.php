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
namespace app\common\enum;

/**
 * 微信公众号枚举
 * Class OfficialAccountEnum
 * @package app\common\enum
 */
class OfficialAccountEnum
{
    /**
     * 菜单类型
     * click - 关键字
     * view - 跳转网页链接
     * miniprogram - 小程序
     */
    const MENU_TYPE = ['click', 'view', 'miniprogram'];

    /**
     * 关注回复
     */
    const REPLY_TYPE_FOLLOW = 1;

    /**
     * 关键字回复
     */
    const REPLY_TYPE_KEYWORD = 2;

    /**
     * 默认回复
     */
    const REPLY_TYPE_DEFAULT= 3;

    /**
     * 回复类型
     * follow - 关注回复
     * keyword - 关键字回复
     * default - 默认回复
     */
    const REPLY_TYPE = [
        self::REPLY_TYPE_FOLLOW => 'follow',
        self::REPLY_TYPE_KEYWORD => 'keyword',
        self::REPLY_TYPE_DEFAULT => 'default'
    ];

    /**
     * 匹配类型 - 全匹配
     */
    const MATCHING_TYPE_FULL = 1;

    /**
     * 匹配类型 - 模糊匹配
     */
    const MATCHING_TYPE_FUZZY = 2;

    /**
     * 消息类型 - 事件
     */
    const MSG_TYPE_EVENT = 'event';

    /**
     * 消息类型 - 文本
     */
    const MSG_TYPE_TEXT = 'text';

    /**
     * 事件类型 - 关注
     */
    const EVENT_SUBSCRIBE = 'subscribe';


    //内容类型
    const CONTENT_TYPE_TEXT = 1;//文本
    const CONTENT_TYPE_IMAGE_TEXT = 2;//图文
    const CONTENT_TYPE_LINK = 3;//超链接

    /**
     * @notes 获取类型英文名称
     * @param $type
     * @return string
     * @author Tab
     * @date 2021/7/29 16:32
     */
    public static function getReplyType($type)
    {
        return self::REPLY_TYPE[$type] ?? '';
    }


    /**
     * @notes 获取回复类型描述
     * @param bool $value
     * @return string|string[]
     * @author 段誉
     * @date 2022/7/25 15:51
     */
    public static function getReplyTypeDesc($value = true)
    {
        $desc = [
            self::REPLY_TYPE_FOLLOW => '关注回复',
            self::REPLY_TYPE_KEYWORD => '关键词回复',
            self::REPLY_TYPE_DEFAULT => '默认回复',
        ];

        if (true === $value) {
            return $desc;
        }
        return $desc[$value] ?? '';
    }


    /**
     * @notes 匹配类型描述
     * @param bool $value
     * @return string|string[]
     * @author 段誉
     * @date 2022/7/25 15:53
     */
    public static function getMatchingTypeDesc($value = true)
    {
        $desc = [
            self::MATCHING_TYPE_FULL => '全匹配',
            self::MATCHING_TYPE_FUZZY => '模糊匹配',
        ];

        if (true === $value) {
            return $desc;
        }
        return $desc[$value] ?? '';
    }

    /**
     * @notes 内容类型
     * @param bool $value
     * @return string|string[]
     * @author ljj
     * @date 2023/6/13 5:38 下午
     */
    public static function getContentTypeDesc($value = true)
    {
        $desc = [
            self::CONTENT_TYPE_TEXT => '文本',
            self::CONTENT_TYPE_IMAGE_TEXT => '图文',
            self::CONTENT_TYPE_LINK => '超链接',
        ];

        if (true === $value) {
            return $desc;
        }
        return $desc[$value] ?? '';
    }
}