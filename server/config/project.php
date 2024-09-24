<?php
return [
    // 系统版本号
    'version' => '2.1.1',
    // 官网
    'website' => [
        'name' => env('project.web_name', 'ChatAI智能对话'), // 网站名称
        'url' => env('project.web_url', 'www.likeshop.cn/'), // 网站地址
        'login_image' => 'resource/image/adminapi/default/login_image.jpg',
        'web_logo' => 'resource/image/adminapi/default/web_logo.jpg', // 网站logo
        'web_favicon' => 'resource/image/adminapi/default/web_favicon.ico', // 网站图标
        'shop_name' => 'ChatAI智能对话', // 商城名称
        'shop_logo' => 'resource/image/adminapi/default/shop_logo.jpg', // 商城图标
        'pc_logo' => 'resource/image/adminapi/default/pc_logo.jpg', // pc_logo
        'pc_ico' => 'resource/image/adminapi/default/web_favicon.ico', // pc_ico
        'pc_title' => 'ChatAI智能对话', // PC网站标题
        'pc_login_image' => 'resource/image/adminapi/default/login_image.jpg',//pc端登录封面
    ],

    // 后台登录
    'admin_login' => [
        // 管理后台登录限制 0-不限制 1-需要限制
        'login_restrictions' => 1,
        // 限制密码错误次数
        'password_error_times' => 5,
        // 限制禁止多少分钟不能登录
        'limit_login_time' => 30,
    ],

    // 唯一标识，密码盐、路径加密等
    'unique_identification' => env('project.unique_identification', 'likeadmin'),

    // 后台管理员token（登录令牌）配置
    'admin_token' => [
        'expire_duration' => 3600 * 8,//管理后台token过期时长(单位秒）
        'be_expire_duration' => 3600,//管理后台token临时过期前时长，自动续期
    ],

    // 商城用户token（登录令牌）配置
    'user_token' => [
        'expire_duration' => 7 * 24 * 60 * 60,//用户token过期时长(单位秒）
        'be_expire_duration' => 3600,//用户token临时过期前时长，自动续期
    ],

    // 列表页
    'lists' => [
        'page_size_max' => 25000,//列表页查询数量限制（列表页每页数量、导出每页数量）
        'page_size' => 25, //默认每页数量
    ],

    // 各种默认图片
    'default_image' => [
        'admin_avatar' => 'resource/image/adminapi/default/avatar.png',
        'user_avatar' => 'resource/image/adminapi/default/default_avatar.png',
        'qq_group' => 'resource/image/adminapi/default/qq_group.png', // qq群
        'customer_service' => 'resource/image/adminapi/default/customer_service.jpg', // 客服
        'menu_admin' => 'resource/image/adminapi/default/menu_admin.png',// 首页快捷菜单-管理员
        'menu_role' => 'resource/image/adminapi/default/menu_role.png', // 首页快捷菜单-角色
        'menu_dept' => 'resource/image/adminapi/default/menu_dept.png',// 首页快捷菜单-部门
        'menu_dict' => 'resource/image/adminapi/default/menu_dict.png',// 首页快捷菜单-字典
        'menu_generator' => 'resource/image/adminapi/default/menu_generator.png',// 首页快捷菜单-代码生成器
        'menu_auth' => 'resource/image/adminapi/default/menu_auth.png',// 首页快捷菜单-菜单权限
        'menu_web' => 'resource/image/adminapi/default/menu_web.png',// 首页快捷菜单-网站信息
        'menu_file' => 'resource/image/adminapi/default/menu_file.png',// 首页快捷菜单-素材中心
        'menu_order' => 'resource/image/adminapi/default/menu_order.png',// 首页快捷菜单-订单管理
        'menu_share' => 'resource/image/adminapi/default/menu_share.png',// 首页快捷菜单-分享奖励
        'menu_invite' => 'resource/image/adminapi/default/menu_invite.png',// 首页快捷菜单-邀请奖励
        'menu_recharge' => 'resource/image/adminapi/default/menu_recharge.png',// 首页快捷菜单-充值套餐
        'menu_member' => 'resource/image/adminapi/default/menu_member.png',// 首页快捷菜单-会员套餐
        'invite' => 'resource/image/api/default/invite.png',// 邀请
        'share' => 'resource/image/api/default/share.png',// 分享
        'chat_logo' => 'resource/image/adminapi/default/chat_logo.png',// 对话图标
        'chat_example' => 'resource/image/adminapi/default/chat_example.png',// 对话示例
        'wechat' => 'resource/image/api/default/wechat.png',// 微信图标
        'wechat_qrcode' => 'resource/image/api/default/wechat_qrcode.png',// 微信二维码图标
        'ali' => 'resource/image/api/default/ali.png',// 支付宝图标
        'ali_qrcode' => 'resource/image/api/default/ali_qrcode.png',// 支付宝二维码图标
    ],

    // 文件上传限制 (图片)
    'file_image' => [
        'jpg', 'png', 'gif', 'jpeg', 'webp'
    ],

    // 文件上传限制 (视频)
    'file_video' => [
        'wmv', 'avi', 'mpg', 'mpeg', '3gp', 'mov', 'mp4', 'flv', 'f4v', 'rmvb', 'mkv'
    ],

    // 登录设置
    'login' => [
        // 登录方式：1-微信登录/公众号授权登录；2-手机号登录
        'login_way' => ['1'],
        // 注册强制绑定手机 0-关闭 1-开启
        'coerce_mobile' => 1,
        // 第三方授权登录 0-关闭 1-开启
        'third_auth' => 1,
        // 微信授权登录 0-关闭 1-开启
        'wechat_auth' => 1,
        // qq授权登录 0-关闭 1-开启
        'qq_auth' => 0,
        // 登录政策协议 0-关闭 1-开启
        'login_agreement' => 1,
        // 短信验证码 0-关闭 1-开启
        'sms_verify' => 0,
    ],

    // 后台装修
    'decorate' => [
        // 底部导航栏样式设置
        'tabbar_style' => ['default_color' => '#999999', 'selected_color' => '#4173ff'],
    ],


    // 产品code
    'product_code' => '339cbed464ba5c70eafa8df34ceefc94',
    'check_domain' => 'https://server.likeshop.cn',

    //注册奖励
    'register_reward' => [
        'status'    => 1,
        'reward'    => 2,
    ],
    //分销设置
    'distribution'  => [
        'is_open'   => 0,
    ],
    // 短信配置
    'sms' => [
        'engine' => 'EMAIL',
        'email' => [
            'status' => 1,
        ]
    ]
];
