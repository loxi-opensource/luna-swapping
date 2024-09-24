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

namespace app\adminapi\logic;

use app\common\enum\PayEnum;
use app\common\enum\WithdrawEnum;
use app\common\enum\YesNoEnum;
use app\common\logic\BaseLogic;
use app\common\model\IndexVisit;
use app\common\model\member\MemberOrder;
use app\common\model\recharge\RechargeOrder;
use app\common\model\user\User;
use app\common\service\ConfigService;
use app\common\service\FileService;
use think\facade\Db;
use think\facade\Request;


/**
 * 工作台
 * Class WorkbenchLogic
 * @package app\adminapi\logic
 */
class WorkbenchLogic extends BaseLogic
{
    /**
     * @notes 工作套
     * @param $adminInfo
     * @return array
     * @author 段誉
     * @date 2021/12/29 15:58
     */
    public static function index()
    {
        return [
            // 版本信息
            'version' => self::versionInfo(),
            // 今日数据
            'today' => self::today(),
            // 待办事项
            'wait' => self::wait(),
            // 常用功能
            'menu' => self::menu(),
            // 近15日销售额
            'business' => self::business(),
            // 近15日访客数
            'visitor' => self::visitor(),
        ];
    }


    /**
     * @notes 待办事项
     * @return string[]
     * @author ljj
     * @date 2023/5/8 2:38 下午
     */
    public static function wait(): array
    {
        return [];
    }


    /**
     * @notes 常用功能
     * @return array[]
     * @author 段誉
     * @date 2021/12/29 16:40
     */
    public static function menu(): array
    {
        return [
            [
                'name' => '换脸任务',
                'image' => FileService::getFileUrlLocal(config('project.default_image.menu_order'), 'local'),
                'url' => '/swap_task'
            ],
            [
                'name' => '模板管理',
                'image' => FileService::getFileUrlLocal(config('project.default_image.menu_file')),
                'url' => '/swap_template/lists'
            ],
            [
                'name' => '玩法管理',
                'image' => FileService::getFileUrlLocal(config('project.default_image.menu_share')),
                'url' => 'swap_template/strategy/lists'
            ],
            [
                'name' => '充值套餐',
                'image' => FileService::getFileUrlLocal(config('project.default_image.menu_recharge')),
                'url' => '/marketing/recharge'
            ],
            [
                'name' => '会员套餐',
                'image' => FileService::getFileUrlLocal(config('project.default_image.menu_member')),
                'url' => '/marketing/vipcombo/combo'
            ],

//            [
//                'name' => '订单管理',
//                'image' => FileService::getFileUrlLocal(config('project.default_image.menu_order'), 'local'),
//                'url' => '/order/rechargeorder'
//            ],
//            [
//                'name' => '分享奖励',
//                'image' => FileService::getFileUrlLocal(config('project.default_image.menu_share')),
//                'url' => '/marketing/share/sharerecord'
//            ],
//            [
//                'name' => '邀请奖励',
//                'image' => FileService::getFileUrlLocal(config('project.default_image.menu_invite')),
//                'url' => '/marketing/invite/inviterecord'
//            ],
//            [
//                'name' => '充值套餐',
//                'image' => FileService::getFileUrlLocal(config('project.default_image.menu_recharge')),
//                'url' => '/marketing/recharge'
//            ],
//            [
//                'name' => '会员套餐',
//                'image' => FileService::getFileUrlLocal(config('project.default_image.menu_member')),
//                'url' => '/marketing/vipcombo/combo'
//            ],
        ];
    }


    /**
     * @notes 版本信息
     * @return array
     * @author 段誉
     * @date 2021/12/29 16:08
     */
    public static function versionInfo(): array
    {
        return [
            'version' => config('project.version'),
            'website' => config('project.website.url'),
            'name' => ConfigService::get('website', 'name'),
            'based' => 'vue3.x、ElementUI、MySQL',
            'channel' => [
                'website' => 'https://www.likeshop.cn/',
                'gitee' => 'https://gitee.com/likeadmin/likeadmin_php',
            ],
            'pc_url' => Request::domain().'/pc/',
        ];
    }


    /**
     * @notes 今日数据
     * @return int[]
     * @author 段誉
     * @date 2021/12/29 16:15
     */
    public static function today(): array
    {
        $member_yesterday_order_num = 0;
        $member_today_order_num = 0;
        $member_yesterday_amount = 0;
        $member_today_amount = 0;

        $recharge_yesterday_order_num = RechargeOrder::where(['pay_status'=>PayEnum::ISPAID])->whereDay('create_time', 'yesterday')->count();
        $recharge_today_order_num = RechargeOrder::where(['pay_status'=>PayEnum::ISPAID])->whereDay('create_time')->count();
        $recharge_yesterday_amount = RechargeOrder::where(['pay_status'=>PayEnum::ISPAID])->whereDay('create_time', 'yesterday')->sum('order_amount');
        $recharge_today_amount = RechargeOrder::where(['pay_status'=>PayEnum::ISPAID])->whereDay('create_time')->sum('order_amount');

        return [
            'time' => date('Y-m-d H:i:s'),

            // 今日销售额
            'today_sales' => round($member_today_amount + $recharge_today_amount,2),
            // 昨日销售额
            'yesterday_sales' => round($member_yesterday_amount + $recharge_yesterday_amount,2),

            // 今日访问量
            'today_visitor' => count(array_unique(IndexVisit::whereDay('create_time')->column('ip'))),
            // 昨日问量
            'yesterday_visitor' => count(array_unique(IndexVisit::whereDay('create_time', 'yesterday')->column('ip'))),

            // 今日新增用户量
            'today_new_user' => User::whereDay('create_time')->count(),
            // 昨日用户量
            'yesterday_new_user' => User::whereDay('create_time', 'yesterday')->count(),

            // 当天订单量 (笔)
            'today_order_num' => round($member_today_order_num + $recharge_today_order_num,2),
            // 昨日订单量
            'yesterday_order_num' => round($member_yesterday_order_num + $recharge_yesterday_order_num,2),
        ];
    }


    /**
     * @notes 销售额
     * @return array
     * @author ljj
     * @date 2023/5/8 2:49 下午
     */
    public static function business(): array
    {
        $time15 = date("Y-m-d",strtotime ("-14 day")).' 00:00:00';
        $time = date("Y-m-d",time()). ' 23:59:59';
        $field = [
            "FROM_UNIXTIME(create_time,'%Y%m%d') as day",
            "sum(order_amount) as today_amount"
        ];
        $member_order = MemberOrder::field($field)
            ->whereTime('create_time', 'between', [$time15, $time])
            ->where('pay_status', YesNoEnum::YES)
            ->group('day')
            ->select()
            ->toArray();
        $recharge_order = RechargeOrder::field($field)
            ->whereTime('create_time', 'between', [$time15, $time])
            ->where('pay_status', YesNoEnum::YES)
            ->group('day')
            ->select()
            ->toArray();
        $member_order_lists = array_column($member_order, 'today_amount', 'day');
        $recharge_order_lists = array_column($recharge_order, 'today_amount', 'day');

        $amountData = [];
        $date = [];
        for($i = 0; $i < 15; $i ++) {
            $time = date("Ymd",strtotime ("-$i day"));
            $date[] = $time;
            $amountData[] = round(($member_order_lists[$time] ?? 0) + ($recharge_order_lists[$time] ?? 0),2);
        }
        return [
            'date' => $date,
            'list' => [
                ['name' => '营业额', 'data' => $amountData]
            ]
        ];
    }


    /**
     * @notes 访问量
     * @return array
     * @author ljj
     * @date 2023/5/8 2:49 下午
     */
    public static function visitor(): array
    {
        $time15 = date("Y-m-d",strtotime ("-14 day")).' 00:00:00';
        $time = date("Y-m-d",time()). ' 23:59:59';

        $subQuery = Db::name('index_visit')
            ->distinct(true)
            ->field("FROM_UNIXTIME(create_time,'%Y%m%d') as day,ip")
            ->whereTime('create_time', 'between', [$time15, $time])
            ->buildSql();
        $lists = Db::table($subQuery . ' a')
            ->field("day,count(ip) as count")
            ->group('day')
            ->select()
            ->toArray();
        $lists = array_column($lists, 'count', 'day');

        $date = [];
        $userData = [];
        for($i = 0; $i < 15; $i ++) {
            $time = date("Ymd",strtotime ("-$i day"));
            $date[] = $time;
            $userData[] = $lists[$time] ?? 0;
        }

        return [
            'date' => $date,
            'list' => [
                ['name' => '访客数', 'data' => $userData]
            ]
        ];
    }
}
