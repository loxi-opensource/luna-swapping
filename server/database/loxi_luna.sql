/*
 Navicat Premium Data Transfer

 Source Server         :
 Source Server Type    : MySQL
 Source Server Version : 80024 (8.0.24)
 Source Host           : 127.0.0.1:3306
 Source Schema         :

 Target Server Type    : MySQL
 Target Server Version : 80024 (8.0.24)
 File Encoding         : 65001

 Date: 24/07/2024 08:46:53
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ai_admin
-- ----------------------------
DROP TABLE IF EXISTS `ai_admin`;
CREATE TABLE `ai_admin`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `root` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否超级管理员 0-否 1-是',
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户头像',
  `account` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '账号',
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '密码',
  `login_time` int NULL DEFAULT NULL COMMENT '最后登录时间',
  `login_ip` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '最后登录ip',
  `multipoint_login` tinyint UNSIGNED NULL DEFAULT 1 COMMENT '是否支持多处登录：1-是；0-否；',
  `disable` tinyint UNSIGNED NULL DEFAULT 0 COMMENT '是否禁用：0-否；1-是；',
  `create_time` int NOT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_admin
-- ----------------------------
INSERT INTO `ai_admin` VALUES (1, 1, '落西', 'uploads/images/20240724/b7b5510e-906f-4506-b4ef-4c78e393ac60.png', 'loxi', 'c4b1b5fca086a0759e0fcb9e6e883efc', 1721776791, '163.142.53.135', 1, 0, 1683801376, 1721776791, NULL);

-- ----------------------------
-- Table structure for ai_admin_dept
-- ----------------------------
DROP TABLE IF EXISTS `ai_admin_dept`;
CREATE TABLE `ai_admin_dept`  (
  `admin_id` int NOT NULL DEFAULT 0 COMMENT '管理员id',
  `dept_id` int NOT NULL DEFAULT 0 COMMENT '部门id',
  PRIMARY KEY (`admin_id`, `dept_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '部门关联表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_admin_dept
-- ----------------------------
INSERT INTO `ai_admin_dept` VALUES (1, 1);

-- ----------------------------
-- Table structure for ai_admin_jobs
-- ----------------------------
DROP TABLE IF EXISTS `ai_admin_jobs`;
CREATE TABLE `ai_admin_jobs`  (
  `admin_id` int NOT NULL COMMENT '管理员id',
  `jobs_id` int NOT NULL COMMENT '岗位id',
  PRIMARY KEY (`admin_id`, `jobs_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '岗位关联表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_admin_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for ai_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `ai_admin_role`;
CREATE TABLE `ai_admin_role`  (
  `admin_id` int NOT NULL COMMENT '管理员id',
  `role_id` int NOT NULL COMMENT '角色id',
  PRIMARY KEY (`admin_id`, `role_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色关联表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_admin_role
-- ----------------------------

-- ----------------------------
-- Table structure for ai_admin_session
-- ----------------------------
DROP TABLE IF EXISTS `ai_admin_session`;
CREATE TABLE `ai_admin_session`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int UNSIGNED NOT NULL COMMENT '用户id',
  `terminal` tinyint(1) NOT NULL DEFAULT 1 COMMENT '客户端类型：1-pc管理后台 2-mobile手机管理后台',
  `token` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '令牌',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  `expire_time` int NOT NULL COMMENT '到期时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `admin_id_client`(`admin_id` ASC, `terminal` ASC) USING BTREE COMMENT '一个用户在一个终端只有一个token',
  UNIQUE INDEX `token`(`token` ASC) USING BTREE COMMENT 'token是唯一的'
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员会话表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_admin_session
-- ----------------------------

-- ----------------------------
-- Table structure for ai_config
-- ----------------------------
DROP TABLE IF EXISTS `ai_config`;
CREATE TABLE `ai_config`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '类型',
  `name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '名称',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '值',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_type`(`type` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 85 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '配置表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_config
-- ----------------------------
INSERT INTO `ai_config` VALUES (1, 'oa_setting', 'name', '', 1683801586, 1683801586);
INSERT INTO `ai_config` VALUES (2, 'oa_setting', 'original_id', '', 1683801586, 1683801586);
INSERT INTO `ai_config` VALUES (3, 'oa_setting', 'qr_code', '', 1683801586, 1683801586);
INSERT INTO `ai_config` VALUES (4, 'oa_setting', 'app_id', '', 1683801586, 1683801586);
INSERT INTO `ai_config` VALUES (5, 'oa_setting', 'app_secret', '', 1683801586, 1683801586);
INSERT INTO `ai_config` VALUES (6, 'oa_setting', 'token', '', 1683801586, 1683802953);
INSERT INTO `ai_config` VALUES (7, 'oa_setting', 'encoding_aes_key', '', 1683801586, 1683802953);
INSERT INTO `ai_config` VALUES (8, 'oa_setting', 'encryption_type', '1', 1683801586, 1683801586);
INSERT INTO `ai_config` VALUES (18, 'oa_setting', 'menu', '', 1683855798, 1684113234);
INSERT INTO `ai_config` VALUES (19, 'recharge', 'status', '1', 1683965809, 1683965809);
INSERT INTO `ai_config` VALUES (20, 'website', 'name', 'LunaAI写真后台', 1684088581, 1721780440);
INSERT INTO `ai_config` VALUES (21, 'website', 'web_favicon', 'uploads/images/20240724/b7b5510e-906f-4506-b4ef-4c78e393ac60.png', 1684088581, 1721776436);
INSERT INTO `ai_config` VALUES (22, 'website', 'web_logo', 'uploads/images/20240724/b7b5510e-906f-4506-b4ef-4c78e393ac60.png', 1684088581, 1721776436);
INSERT INTO `ai_config` VALUES (23, 'website', 'login_image', 'uploads/images/20240724/b7b5510e-906f-4506-b4ef-4c78e393ac60.png', 1684088581, 1721776436);
INSERT INTO `ai_config` VALUES (24, 'website', 'shop_name', 'Luna', 1684088581, 1707211660);
INSERT INTO `ai_config` VALUES (25, 'website', 'shop_logo', 'uploads/images/20240724/b7b5510e-906f-4506-b4ef-4c78e393ac60.png', 1684088581, 1721776436);
INSERT INTO `ai_config` VALUES (26, 'website', 'pc_logo', 'uploads/images/20240724/b7b5510e-906f-4506-b4ef-4c78e393ac60.png', 1684088581, 1721776436);
INSERT INTO `ai_config` VALUES (27, 'website', 'pc_title', 'Luna', 1684088581, 1707211660);
INSERT INTO `ai_config` VALUES (28, 'website', 'pc_ico', 'uploads/images/20240724/b7b5510e-906f-4506-b4ef-4c78e393ac60.png', 1684088581, 1721776436);
INSERT INTO `ai_config` VALUES (29, 'website', 'pc_desc', '', 1684088581, 1684088581);
INSERT INTO `ai_config` VALUES (30, 'website', 'pc_key', '', 1684088581, 1684088581);
INSERT INTO `ai_config` VALUES (31, 'website', 'contacts', '落西源码', 1684088581, 1684088581);
INSERT INTO `ai_config` VALUES (32, 'website', 'mobile', '15521226475', 1684088581, 1684088581);
INSERT INTO `ai_config` VALUES (39, 'register_reward', 'status', '1', 1685712397, 1685712397);
INSERT INTO `ai_config` VALUES (40, 'register_reward', 'reward', '10', 1685712397, 1685712397);
INSERT INTO `ai_config` VALUES (47, 'mnp_setting', 'name', 'LunaAI写真', 1688803453, 1721777818);
INSERT INTO `ai_config` VALUES (48, 'mnp_setting', 'original_id', '', 1688803454, 1688803454);
INSERT INTO `ai_config` VALUES (49, 'mnp_setting', 'qr_code', '', 1688803454, 1688803454);
INSERT INTO `ai_config` VALUES (50, 'mnp_setting', 'app_id', '', 1688803454, 1721778479);
INSERT INTO `ai_config` VALUES (51, 'mnp_setting', 'app_secret', '', 1688803454, 1721778479);
INSERT INTO `ai_config` VALUES (52, 'storage', 'default', 'local', 1689608530, 1721776117);
INSERT INTO `ai_config` VALUES (53, 'storage', 'aliyun', '', 1689608530, 1689608530);
INSERT INTO `ai_config` VALUES (54, 'douyin_mnp_setting', 'app_id', '', 1688803454, 1688803454);
INSERT INTO `ai_config` VALUES (55, 'douyin_mnp_setting', 'app_secret', '', 1688803454, 1688803454);
INSERT INTO `ai_config` VALUES (56, 'agreement', 'service_title', 'Luna AI写真 用户服务协议', 1692008212, 1692008212);
INSERT INTO `ai_config` VALUES (57, 'agreement', 'service_status', '0', 1692008212, 1692008212);
INSERT INTO `ai_config` VALUES (58, 'agreement', 'service_content', '', 1692008212, 1692008212);
INSERT INTO `ai_config` VALUES (59, 'agreement', 'privacy_title', 'Luna AI写真 用户隐私政策', 1692008212, 1692008212);
INSERT INTO `ai_config` VALUES (60, 'agreement', 'privacy_status', '0', 1692008212, 1692008212);
INSERT INTO `ai_config` VALUES (61, 'agreement', 'privacy_content', '', 1692008212, 1692008212);
INSERT INTO `ai_config` VALUES (62, 'homepage', 'default', '[{\"content\":\"\",\"img_pre_url\":\"https://luna-frontend-static-resource.oss-cn-shenzhen.aliyuncs.com/christmas.png\",\"img_url\":\"https://luna-frontend-static-resource.oss-cn-shenzhen.aliyuncs.com/christmas.png\",\"label\":\"christmas\",\"multi_select\":true,\"tag_group_id\":11,\"title\":\"龙年新春限免\",\"use_num\":\"\"},{\"label\":\"portrait\",\"img_url\":\"https://luna-frontend-static-resource.oss-cn-shenzhen.aliyuncs.com/portrait-type-bg.png\",\"img_pre_url\":\"https://luna-frontend-static-resource.oss-cn-shenzhen.aliyuncs.com/pre-portrait-type-bg.png\",\"title\":\"靓丽肖像\",\"content\":\"6秒即美，多重魅力随心选\",\"use_num\":\"100w+人正在使用\",\"multi_select\":true,\"tag_group_id\":1},{\"label\":\"gallary\",\"img_url\":\"https://luna-frontend-static-resource.oss-cn-shenzhen.aliyuncs.com/gallary-type-bg.png\",\"img_pre_url\":\"https://luna-frontend-static-resource.oss-cn-shenzhen.aliyuncs.com/pre-gallary-type-bg.png\",\"title\":\"定妆照\",\"content\":\"上传一张，美丽惊艳全场\",\"use_num\":\"21w+人正在使用\",\"multi_select\":false,\"tag_group_id\":2}]', 1689608530, 1689608530);
INSERT INTO `ai_config` VALUES (68, 'internal_testing_member', 'default', '[1]', 1689608530, 1689608530);
INSERT INTO `ai_config` VALUES (69, 'share', 'default', '[{\"title\":\"金龙贺岁添财源，Luna帮您制作龙年专属拜年写真~\",\"image\":\"https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/images/20240209/c9e0bc57-6804-4e5d-819b-0789c7688cd2.png\"},{\"title\":\"在这难得的假期时间用Luna记录下父母的幸福时刻！\",\"image\":\"https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/images/20240209/9d998bbd-a95c-4d5d-8058-b9e66aa27c18.png\"},{\"title\":\"羡慕抖音上的新春装小姐姐吗,来Luna, 想怎么变就怎么变\",\"image\":\"https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/images/20240209/88e43926-1627-46ab-a9c9-7402f4f82b28.png\"},{\"title\":\"龙年新春喜气洋，神州大地换新装,来Luna一键帮你换成新春装！\",\"image\":\"https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/images/20240209/6ecff36d-8942-45bf-b28d-82d208620097.png\"}]', 1689608530, 1689608530);
INSERT INTO `ai_config` VALUES (70, 'website', 'pc_login_image', '', 1707211660, 1721776436);
INSERT INTO `ai_config` VALUES (71, 'image_check', 'audit_open', '0', 1721634700, 1721636491);
INSERT INTO `ai_config` VALUES (72, 'image_check', 'access_key', '', 1721634700, 1721634786);
INSERT INTO `ai_config` VALUES (73, 'image_check', 'secret_key', '', 1721634700, 1721634786);
INSERT INTO `ai_config` VALUES (74, 'image_check', 'endpoint', '', 1721636441, 1721636441);
INSERT INTO `ai_config` VALUES (75, 'image_check', 'region_id', '', 1721636441, 1721637743);
INSERT INTO `ai_config` VALUES (76, 'luna_service', 'secret', '', 1721638266, 1721778682);
INSERT INTO `ai_config` VALUES (77, 'luna_service', 'secret_key', '', 1721638266, 1721778682);
INSERT INTO `ai_config` VALUES (78, 'storage', 'local', '[]', 1721776117, 1721776117);
INSERT INTO `ai_config` VALUES (79, 'web_page', 'status', '0', 1721778044, 1721778044);
INSERT INTO `ai_config` VALUES (80, 'web_page', 'page_status', '0', 1721778044, 1721778044);
INSERT INTO `ai_config` VALUES (81, 'web_page', 'page_url', '', 1721778044, 1721778044);
INSERT INTO `ai_config` VALUES (82, 'pc_page', 'status', '0', 1721778065, 1721778065);
INSERT INTO `ai_config` VALUES (83, 'pc_page', 'page_status', '0', 1721778065, 1721778065);
INSERT INTO `ai_config` VALUES (84, 'pc_page', 'page_url', '', 1721778065, 1721778065);

-- ----------------------------
-- Table structure for ai_decorate_page
-- ----------------------------
DROP TABLE IF EXISTS `ai_decorate_page`;
CREATE TABLE `ai_decorate_page`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type` tinyint UNSIGNED NOT NULL DEFAULT 10 COMMENT '页面类型 1=商城首页, 2=个人中心, 3=客服设置 4-PC首页',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '页面名称',
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '页面数据',
  `create_time` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int UNSIGNED NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '装修页面配置表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_decorate_page
-- ----------------------------
INSERT INTO `ai_decorate_page` VALUES (1, 1, '个人中心', '[{\"id\":\"lguipe8u4rugb\",\"title\":\"用户信息\",\"name\":\"user-info\",\"disabled\":1,\"content\":{},\"styles\":{}},{\"id\":\"lguipe8uqmvd3\",\"title\":\"vip会员\",\"name\":\"open-vip\",\"content\":{\"enabled\":1},\"styles\":{}},{\"id\":\"lguipe8ulnjrc\",\"title\":\"我的服务\",\"name\":\"my-service\",\"content\":{\"style\":2,\"title\":\"我的服务\",\"data\":[{\"image\":\"uploads/images/20230426/20230426160618005328706.png\",\"name\":\"我的收藏\",\"link\":{\"path\":\"/pages/collection/collection\",\"name\":\"我的收藏\",\"type\":\"shop\"}},{\"image\":\"uploads/images/20230426/20230426160618ae3ce9306.png\",\"name\":\"意见反馈\",\"link\":{\"path\":\"/packages/pages/feedback/feedback\",\"name\":\"意见反馈\",\"type\":\"shop\"}},{\"image\":\"uploads/images/20230426/20230426160618fdef75580.png\",\"name\":\"联系客服\",\"link\":{\"path\":\"/pages/customer_service/customer_service\",\"name\":\"联系客服\",\"type\":\"shop\"}},{\"image\":\"uploads/images/20230426/20230426160618fff086162.png\",\"name\":\"关于我们\",\"link\":{\"path\":\"/pages/as_us/as_us\",\"name\":\"关于我们\",\"type\":\"shop\"}}]},\"styles\":{}},{\"id\":\"lguipe8ufqj00\",\"title\":\"个人中心广告图\",\"name\":\"user-banner\",\"content\":{\"enabled\":0,\"data\":[{\"image\":\"\",\"name\":\"\",\"link\":{}}]},\"styles\":{}}]', 1661757188, 1682582182);
INSERT INTO `ai_decorate_page` VALUES (2, 2, '会员中心', '[{\"title\":\"走马灯\",\"name\":\"vip-top\",\"disabled\":0,\"content\":{\"enable\":2,\"enabled\":1},\"styles\":{}},{\"title\":\"\",\"name\":\"vip-body\",\"disabled\":1,\"content\":{\"style\":1},\"styles\":{}},{\"title\":\"会员权益\",\"name\":\"vip-advantage\",\"content\":{\"enabled\":1,\"name\":\"会员权益\",\"data\":[{\"image\":\"uploads/images/20230426/20230426161049b7b921148.png\",\"name\":\"与AI畅聊\"},{\"image\":\"uploads/images/20230426/20230426161049ba0468649.png\",\"name\":\"人工智能\"},{\"image\":\"uploads/images/20230426/20230426161049cecb06682.png\",\"name\":\"智能对话\"},{\"image\":\"uploads/images/20230426/20230426161049e2c763351.png\",\"name\":\"超低延迟\"},{\"image\":\"uploads/images/20230426/20230426161049011657391.png\",\"name\":\"独享接口\"},{\"image\":\"uploads/images/20230426/202304261610492c57b4038.png\",\"name\":\"专属客服\"}],\"style\":2,\"enable\":1},\"styles\":{}},{\"title\":\"会员说明\",\"name\":\"vip-notice\",\"content\":{\"enabled\":1,\"name\":\"开通会员须知\",\"data\":\"1.会员权益为虚拟服务，套餐开通成功后不可退换，敬请谅解；\\n2.扣款成功后会员权益即刻生效若未即刻生效请等待30分钟或联系客服;\\n3.由于AI需要大量计算资源，高峰期可能会服务波动，系统可能会降级服务;\\n4.开发不易,您的支持将保证我们持续运营努力变得更好;\\n5.在法律允许范围内，本公司对本产品享有最终解释权。\"},\"styles\":{}},{\"title\":\"用户评价\",\"name\":\"vip-evaluate\",\"content\":{\"enabled\":1,\"data\":5},\"styles\":{}}]', 1661757188, 1682563074);
INSERT INTO `ai_decorate_page` VALUES (3, 3, '客服设置', '[{\"title\":\"客服设置\",\"name\":\"customer-service\",\"content\":{\"title\":\"添加开发者小哥哥微信有惊喜哦\",\"time\":\"7*24小时 / 微信同号\",\"mobile\":\"15521226475\",\"qrcode\":\"uploads/images/20230602/202306022230272a1308688.jpg\",\"title_status\":\"1\",\"mobile_status\":\"1\",\"time_status\":\"1\"},\"styles\":{}}]', 1661757188, 1685716278);
INSERT INTO `ai_decorate_page` VALUES (4, 4, '系统风格', '{\"themeColorId\":2,\"topTextColor\":\"black\",\"navigationBarColor\":\"#EA2929\",\"themeColor1\":\"#02AAB0\",\"themeColor2\":\"#00CDAC\",\"buttonColor\":\"white\"}', 1661757188, 1685419711);
INSERT INTO `ai_decorate_page` VALUES (5, 5, '邀请海报', '[{\"id\":\"lhzliq33nbu2y\",\"title\":\"邀请海报\",\"name\":\"invite-poster\",\"content\":{\"default\":1,\"defaultUrl1\":\"/resource/image/adminapi/default/invite_backdend01.png\",\"defaultUrl2\":\"/resource/image/adminapi/default/invite_backdend02.png\",\"defaultQrcode\":\"/resource/image/adminapi/default/qrcode.png\",\"poster\":1,\"posterUrl\":\"\",\"data\":{\"content\":\"邀请您前来体验\",\"x\":81,\"y\":413},\"showData\":\"1\",\"code\":{\"x\":82,\"y\":291}},\"styles\":{}},{\"id\":\"lhzliq33onwgd\",\"title\":\"规则说明\",\"name\":\"invite-rule\",\"disabled\":0,\"content\":{\"enabled\":1,\"name\":\"邀请说明\",\"data\":\"好友通过您分享的邀请分销海报注册登录后，Ta将永久成为您的下级分销商，未来Ta产生的订单佣金您都有奖励，会自动计入您的账号中！!\"},\"styles\":{}}]', 1661757188, 1685097935);
INSERT INTO `ai_decorate_page` VALUES (6, 6, '对话海报', '[{\"id\":\"li34ldqby4fkb\",\"title\":\"对话海报\",\"name\":\"dialogue-poster\",\"content\":{\"default\":1,\"defaultUrl1\":\"/resource/image/adminapi/default/dialogue_backdend01.png\",\"defaultUrl2\":\"/resource/image/adminapi/default/dialogue_backdend02.png\",\"defaultAvatar\":\"/resource/image/adminapi/default/user_avatar.jpeg\",\"defaultQrcode\":\"/resource/image/adminapi/default/qrcode.png\",\"poster\":1,\"posterUrl\":\"\",\"data\":\"邀请您前来体验\",\"showData\":\"1\"},\"styles\":{}}]', 1661757188, 1685092790);

-- ----------------------------
-- Table structure for ai_decorate_tabbar
-- ----------------------------
DROP TABLE IF EXISTS `ai_decorate_tabbar`;
CREATE TABLE `ai_decorate_tabbar`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '页面类型:1-移动端导航栏；2-PC端导航栏',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '导航名称',
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '已选图标',
  `create_time` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '装修底部导航表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_decorate_tabbar
-- ----------------------------
INSERT INTO `ai_decorate_tabbar` VALUES (1, 1, '移动端导航栏', '{\"style\":{\"default_color\":\"#999999\",\"selected_color\":\"#FFBA3C\"},\"list\":[{\"name\":\"问答\",\"selected\":\"resource/image/adminapi/default/question.png\",\"unselected\":\"resource/image/adminapi/default/select-question.png\",\"link\":{\"path\":\"/pages/index/index\",\"name\":\"首页\",\"type\":\"shop\"},\"is_show\":\"1\"},{\"name\":\"创作\",\"selected\":\"resource/image/adminapi/default/creation.png\",\"unselected\":\"resource/image/adminapi/default/select-creation.png\",\"link\":{\"path\":\"/pages/ai_creation/ai_creation\",\"name\":\"创作中心\",\"type\":\"shop\"},\"is_show\":\"1\"},{\"name\":\"技能\",\"selected\":\"resource/image/adminapi/default/skill.png\",\"unselected\":\"resource/image/adminapi/default/select-skill.png\",\"link\":{\"path\":\"/pages/skills/skills\",\"name\":\"技能大全\",\"type\":\"shop\"},\"is_show\":\"1\"},{\"name\":\"绘画\",\"selected\":\"resource/image/adminapi/default/draw.png\",\"unselected\":\"resource/image/adminapi/default/select-draw.png\",\"link\":{\"path\":\"/pages/drawing/drawing\",\"name\":\"绘画\",\"type\":\"shop\"},\"is_show\":\"1\"},{\"name\":\"我的\",\"selected\":\"resource/image/adminapi/default/my.png\",\"unselected\":\"resource/image/adminapi/default/select-my.png\",\"link\":{\"path\":\"/pages/user/user\",\"name\":\"个人中心\",\"type\":\"shop\"},\"is_show\":\"1\"}]}', 1688457444, 1688457444);
INSERT INTO `ai_decorate_tabbar` VALUES (2, 2, 'PC端导航栏', '{\"nav\":[{\"name\":\"对话\",\"selected\":\"resource/image/adminapi/default/pc-chat.png\",\"unselected\":\"resource/image/adminapi/default/pc-select-chat.png\",\"is_show\":\"1\",\"link\":{\"path\":\"/\",\"name\":\"首页对话\",\"type\":\"shop\"}},{\"name\":\"创作\",\"selected\":\"resource/image/adminapi/default/pc-creation.png\",\"unselected\":\"resource/image/adminapi/default/pc-select-creation.png\",\"is_show\":\"1\",\"link\":{\"path\":\"/create\",\"name\":\"AI创作\",\"type\":\"shop\"}},{\"name\":\"技能\",\"selected\":\"resource/image/adminapi/default/pc-skill.png\",\"unselected\":\"resource/image/adminapi/default/pc-select-skill.png\",\"is_show\":\"1\",\"link\":{\"path\":\"/skill\",\"name\":\"AI技能\",\"type\":\"shop\"}},{\"name\":\"绘画\",\"selected\":\"resource/image/adminapi/default/pc-draw.png\",\"unselected\":\"resource/image/adminapi/default/pc-select-draw.png\",\"is_show\":\"1\",\"link\":{\"path\":\"/drawing\",\"name\":\"AI绘画\",\"type\":\"shop\"}}],\"menu\":[{\"name\":\"开通会员\",\"selected\":\"resource/image/adminapi/default/pc-member.png\",\"unselected\":\"resource/image/adminapi/default/pc-select-member.png\",\"is_show\":\"1\",\"link\":{\"path\":\"/openvip\",\"name\":\"开通会员\",\"type\":\"shop\"}},{\"name\":\"充值/奖励\",\"selected\":\"resource/image/adminapi/default/pc-recharge.png\",\"unselected\":\"resource/image/adminapi/default/pc-select-recharge.png\",\"is_show\":\"1\",\"link\":{\"path\":\"/recharge\",\"name\":\"充值奖励\",\"type\":\"shop\"}},{\"name\":\"分销推广\",\"selected\":\"resource/image/adminapi/default/pc-distribution.png\",\"unselected\":\"resource/image/adminapi/default/pc-select-distribution.png\",\"is_show\":\"1\",\"link\":{\"path\":\"/promotion/distribution\",\"name\":\"分销推广\",\"type\":\"shop\"}},{\"name\":\"我的收藏\",\"selected\":\"resource/image/adminapi/default/pc-collection.png\",\"unselected\":\"resource/image/adminapi/default/pc-select-collection.png\",\"is_show\":\"1\",\"link\":{\"path\":\"/user/collection\",\"name\":\"我的收藏\",\"type\":\"shop\"}},{\"name\":\"建议反馈\",\"selected\":\"resource/image/adminapi/default/pc-opinion.png\",\"unselected\":\"resource/image/adminapi/default/pc-select-opinion.png\",\"is_show\":\"1\",\"link\":{\"path\":\"/user/opinion\",\"name\":\"意见反馈\",\"type\":\"shop\"}}]}', 1688457444, 1688457444);

-- ----------------------------
-- Table structure for ai_dept
-- ----------------------------
DROP TABLE IF EXISTS `ai_dept`;
CREATE TABLE `ai_dept`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '部门名称',
  `pid` bigint NOT NULL DEFAULT 0 COMMENT '上级部门id',
  `sort` int NOT NULL DEFAULT 0 COMMENT '排序',
  `leader` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '负责人',
  `mobile` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '联系电话',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '部门状态（0停用 1正常）',
  `create_time` int NOT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '部门表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_dept
-- ----------------------------
INSERT INTO `ai_dept` VALUES (1, '公司', 0, 0, 'boss', '12345698745', 1, 1650592684, 1653640368, NULL);

-- ----------------------------
-- Table structure for ai_dept_jobs
-- ----------------------------
DROP TABLE IF EXISTS `ai_dept_jobs`;
CREATE TABLE `ai_dept_jobs`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '岗位名称',
  `code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '岗位编码',
  `sort` int NULL DEFAULT 0 COMMENT '显示顺序',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态（0停用 1正常）',
  `remark` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '备注',
  `create_time` int NOT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '岗位表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_dept_jobs
-- ----------------------------
INSERT INTO `ai_dept_jobs` VALUES (1, '全栈开发', 'fs-1', 0, 1, '', 1717919833, 1717919833, NULL);

-- ----------------------------
-- Table structure for ai_dev_crontab
-- ----------------------------
DROP TABLE IF EXISTS `ai_dev_crontab`;
CREATE TABLE `ai_dev_crontab`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '定时任务名称',
  `type` tinyint(1) NOT NULL COMMENT '类型 1-定时任务',
  `system` tinyint NULL DEFAULT 0 COMMENT '是否系统任务 0-否 1-是',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '备注',
  `command` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '命令内容',
  `params` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '参数',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态 1-运行 2-停止 3-错误',
  `expression` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '运行规则',
  `error` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '运行失败原因',
  `last_time` int NULL DEFAULT NULL COMMENT '最后执行时间',
  `time` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '实时执行时长',
  `max_time` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '最大执行时长',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '计划任务表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_dev_crontab
-- ----------------------------
INSERT INTO `ai_dev_crontab` VALUES (1, '内容审核', 1, 0, '', 'content_censor', '', 1, '*/10 * * * *', '', 1687346404, '0', '336.27', 1687164568, 1687246140, NULL);
INSERT INTO `ai_dev_crontab` VALUES (2, '商家转账到零钱查询', 1, 0, '', 'wechat_merchant_transfer', '', 1, '* * * * *', '', 1687346469, '6.46', '7.3', 1687253388, 1687253388, NULL);

-- ----------------------------
-- Table structure for ai_dev_pay_config
-- ----------------------------
DROP TABLE IF EXISTS `ai_dev_pay_config`;
CREATE TABLE `ai_dev_pay_config`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '模版名称',
  `pay_way` tinyint(1) NOT NULL COMMENT '支付方式:1-余额支付;2-微信支付;3-支付宝支付;',
  `config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '对应支付配置(json字符串)',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '图标',
  `sort` int NULL DEFAULT NULL COMMENT '排序',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_dev_pay_config
-- ----------------------------
INSERT INTO `ai_dev_pay_config` VALUES (1, '微信支付', 2, '{\"interface_version\":\"v3\",\"merchant_type\":\"ordinary_merchant\",\"mch_id\":\"\",\"pay_sign_key\":\"\",\"apiclient_cert\":\"\",\"apiclient_key\":\"\"}', 'https://test.luna-api.duimixinyifu.com/uploads/images/20240724/3bc1e26b-67ee-4ee7-b0bd-fee624a33f7b.png', 101, '微信支付备注');
INSERT INTO `ai_dev_pay_config` VALUES (2, '支付宝支付', 3, '', '/resource/image/adminapi/pay/alipay.jpg', 100, '支付宝支付备注');

-- ----------------------------
-- Table structure for ai_dev_pay_way
-- ----------------------------
DROP TABLE IF EXISTS `ai_dev_pay_way`;
CREATE TABLE `ai_dev_pay_way`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `pay_config_id` int NOT NULL COMMENT '支付配置ID',
  `scene` tinyint(1) NOT NULL COMMENT '场景:1-微信小程序;2-微信公众号;3-H5;4-PC;5-APP;',
  `is_default` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否默认支付:0-否;1-是;',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态:0-关闭;1-开启;',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_dev_pay_way
-- ----------------------------
INSERT INTO `ai_dev_pay_way` VALUES (3, 1, 1, 1, 1);
INSERT INTO `ai_dev_pay_way` VALUES (4, 1, 2, 1, 1);
INSERT INTO `ai_dev_pay_way` VALUES (7, 1, 4, 1, 1);
INSERT INTO `ai_dev_pay_way` VALUES (8, 2, 4, 0, 1);
INSERT INTO `ai_dev_pay_way` VALUES (9, 2, 2, 0, 1);
INSERT INTO `ai_dev_pay_way` VALUES (10, 1, 3, 1, 1);
INSERT INTO `ai_dev_pay_way` VALUES (11, 2, 3, 0, 1);

-- ----------------------------
-- Table structure for ai_dict_data
-- ----------------------------
DROP TABLE IF EXISTS `ai_dict_data`;
CREATE TABLE `ai_dict_data`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '数据名称',
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '数据值',
  `type_id` int NOT NULL COMMENT '字典类型id',
  `type_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '字典类型',
  `sort` int NULL DEFAULT 0 COMMENT '排序值',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态 0-停用 1-正常',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '备注',
  `create_time` int NOT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '字典数据表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_dict_data
-- ----------------------------
INSERT INTO `ai_dict_data` VALUES (1, '隐藏', '0', 1, 'show_status', 0, 1, '', 1656381543, 1656381543, NULL);
INSERT INTO `ai_dict_data` VALUES (2, '显示', '1', 1, 'show_status', 0, 1, '', 1656381550, 1656381550, NULL);
INSERT INTO `ai_dict_data` VALUES (3, '进行中', '0', 2, 'business_status', 0, 1, '', 1656381410, 1656381410, NULL);
INSERT INTO `ai_dict_data` VALUES (4, '成功', '1', 2, 'business_status', 0, 1, '', 1656381437, 1656381437, NULL);
INSERT INTO `ai_dict_data` VALUES (5, '失败', '2', 2, 'business_status', 0, 1, '', 1656381449, 1656381449, NULL);
INSERT INTO `ai_dict_data` VALUES (6, '待处理', '0', 3, 'event_status', 0, 1, '', 1656381212, 1656381212, NULL);
INSERT INTO `ai_dict_data` VALUES (7, '已处理', '1', 3, 'event_status', 0, 1, '', 1656381315, 1656381315, NULL);
INSERT INTO `ai_dict_data` VALUES (8, '拒绝处理', '2', 3, 'event_status', 0, 1, '', 1656381331, 1656381331, NULL);
INSERT INTO `ai_dict_data` VALUES (9, '禁用', '1', 4, 'system_disable', 0, 1, '', 1656312030, 1656312030, NULL);
INSERT INTO `ai_dict_data` VALUES (10, '正常', '0', 4, 'system_disable', 0, 1, '', 1656312040, 1656312040, NULL);
INSERT INTO `ai_dict_data` VALUES (11, '未知', '0', 5, 'sex', 0, 1, '', 1656062988, 1656062988, NULL);
INSERT INTO `ai_dict_data` VALUES (12, '男', '1', 5, 'sex', 0, 1, '', 1656062999, 1656062999, NULL);
INSERT INTO `ai_dict_data` VALUES (13, '女', '2', 5, 'sex', 0, 1, '', 1656063009, 1656063009, NULL);
INSERT INTO `ai_dict_data` VALUES (14, '失败', '3', 6, 'task_status', 0, 1, '', 1699324927, 1699324927, NULL);
INSERT INTO `ai_dict_data` VALUES (15, '成功', '2', 6, 'task_status', 0, 1, '', 1699324936, 1699324936, NULL);
INSERT INTO `ai_dict_data` VALUES (16, '处理中', '1', 6, 'task_status', 0, 1, '', 1699324971, 1699324971, NULL);
INSERT INTO `ai_dict_data` VALUES (17, '是', '1', 7, 'is_or_not', 0, 1, '', 1699325013, 1699325013, NULL);
INSERT INTO `ai_dict_data` VALUES (18, '-', '0', 7, 'is_or_not', 0, 1, '', 1699325026, 1699325026, NULL);

-- ----------------------------
-- Table structure for ai_dict_type
-- ----------------------------
DROP TABLE IF EXISTS `ai_dict_type`;
CREATE TABLE `ai_dict_type`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字典名称',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字典类型名称',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态 0-停用 1-正常',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '备注',
  `create_time` int NOT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '字典类型表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_dict_type
-- ----------------------------
INSERT INTO `ai_dict_type` VALUES (1, '显示状态', 'show_status', 1, '', 1656381520, 1656381520, NULL);
INSERT INTO `ai_dict_type` VALUES (2, '业务状态', 'business_status', 1, '', 1656381393, 1656381393, NULL);
INSERT INTO `ai_dict_type` VALUES (3, '事件状态', 'event_status', 1, '', 1656381075, 1656381075, NULL);
INSERT INTO `ai_dict_type` VALUES (4, '禁用状态', 'system_disable', 1, '', 1656311838, 1656311838, NULL);
INSERT INTO `ai_dict_type` VALUES (5, '用户性别', 'sex', 1, '', 1656062946, 1656380925, NULL);
INSERT INTO `ai_dict_type` VALUES (6, '任务状态', 'task_status', 1, '', 1699324883, 1699325156, NULL);
INSERT INTO `ai_dict_type` VALUES (7, '是否', 'is_or_not', 1, '', 1699324999, 1699325077, NULL);

-- ----------------------------
-- Table structure for ai_failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `ai_failed_jobs`;
CREATE TABLE `ai_failed_jobs`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `connection` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `queue` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fail_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for ai_feedback
-- ----------------------------
DROP TABLE IF EXISTS `ai_feedback`;
CREATE TABLE `ai_feedback`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT '用户ID',
  `type` tinyint(1) NOT NULL COMMENT '反馈类型：1-故障；2-建议；3-投诉；',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '反馈内容',
  `mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '联系方式',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  `images` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '图片文件ID列表。多个逗号分隔',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_uid`(`user_id` ASC) USING BTREE,
  INDEX `idx_mobile`(`mobile` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 92 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '意见反馈表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_feedback
-- ----------------------------

-- ----------------------------
-- Table structure for ai_file
-- ----------------------------
DROP TABLE IF EXISTS `ai_file`;
CREATE TABLE `ai_file`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `cid` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '类目ID',
  `source_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '上传者id',
  `source` tinyint(1) NOT NULL DEFAULT 0 COMMENT '来源类型[0-后台,1-用户]',
  `type` tinyint UNSIGNED NOT NULL DEFAULT 10 COMMENT '类型[10=图片, 20=视频]',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '文件名称',
  `uri` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '文件路径',
  `create_time` int UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15569 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '文件表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_file
-- ----------------------------
INSERT INTO `ai_file` VALUES (15551, 1, 0, 0, 10, 'logo.png', 'uploads/images/20240724/b7b5510e-906f-4506-b4ef-4c78e393ac60.png', 1721776364, 1721776364, NULL);
INSERT INTO `ai_file` VALUES (15552, 1, 0, 0, 10, 'wechatpay.png', 'uploads/images/20240724/3bc1e26b-67ee-4ee7-b0bd-fee624a33f7b.png', 1721777289, 1721777289, NULL);
INSERT INTO `ai_file` VALUES (15553, 1, 0, 0, 10, 'alipay.jpg', 'uploads/images/20240724/aedcd9ca-a188-4978-9672-4a4b5a9b8984.jpg', 1721777292, 1721777292, NULL);

-- ----------------------------
-- Table structure for ai_file_cate
-- ----------------------------
DROP TABLE IF EXISTS `ai_file_cate`;
CREATE TABLE `ai_file_cate`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `pid` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级ID',
  `type` tinyint UNSIGNED NOT NULL DEFAULT 10 COMMENT '类型[10=图片，20=视频，30=文件]',
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `create_time` int UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int UNSIGNED NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int UNSIGNED NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文件分类表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_file_cate
-- ----------------------------
INSERT INTO `ai_file_cate` VALUES (1, 0, 10, '系统素材', 1682592809, 1721776218, NULL);

-- ----------------------------
-- Table structure for ai_generate_column
-- ----------------------------
DROP TABLE IF EXISTS `ai_generate_column`;
CREATE TABLE `ai_generate_column`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'id',
  `table_id` int NOT NULL DEFAULT 0 COMMENT '表id',
  `column_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字段名称',
  `column_comment` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字段描述',
  `column_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字段类型',
  `is_required` tinyint(1) NULL DEFAULT 0 COMMENT '是否必填 0-非必填 1-必填',
  `is_pk` tinyint(1) NULL DEFAULT 0 COMMENT '是否为主键 0-不是 1-是',
  `is_insert` tinyint(1) NULL DEFAULT 0 COMMENT '是否为插入字段 0-不是 1-是',
  `is_update` tinyint(1) NULL DEFAULT 0 COMMENT '是否为更新字段 0-不是 1-是',
  `is_lists` tinyint(1) NULL DEFAULT 0 COMMENT '是否为列表字段 0-不是 1-是',
  `is_query` tinyint(1) NULL DEFAULT 0 COMMENT '是否为查询字段 0-不是 1-是',
  `query_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '=' COMMENT '查询类型',
  `view_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'input' COMMENT '显示类型',
  `dict_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '字典类型',
  `create_time` int NOT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 30 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '代码生成表字段信息表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_generate_column
-- ----------------------------

-- ----------------------------
-- Table structure for ai_generate_table
-- ----------------------------
DROP TABLE IF EXISTS `ai_generate_table`;
CREATE TABLE `ai_generate_table`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'id',
  `table_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '表名称',
  `table_comment` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '表描述',
  `template_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '模板类型 0-单表(curd) 1-树表(curd)',
  `author` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '作者',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '备注',
  `generate_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '生成方式  0-压缩包下载 1-生成到模块',
  `module_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '模块名',
  `class_dir` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '类目录名',
  `class_comment` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '类描述',
  `admin_id` int NULL DEFAULT 0 COMMENT '管理员id',
  `menu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '菜单配置',
  `delete` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '删除配置',
  `tree` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '树表配置',
  `relations` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '关联配置',
  `create_time` int NOT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '代码生成表信息表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_generate_table
-- ----------------------------

-- ----------------------------
-- Table structure for ai_hot_search
-- ----------------------------
DROP TABLE IF EXISTS `ai_hot_search`;
CREATE TABLE `ai_hot_search`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '关键词',
  `sort` smallint UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序号',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '热门搜索表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_hot_search
-- ----------------------------

-- ----------------------------
-- Table structure for ai_index_visit
-- ----------------------------
DROP TABLE IF EXISTS `ai_index_visit`;
CREATE TABLE `ai_index_visit`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '访客ip地址',
  `terminal` tinyint(1) NOT NULL COMMENT '访问终端',
  `visit` int NOT NULL COMMENT '浏览量',
  `create_time` int NULL DEFAULT NULL COMMENT '访问时间',
  `update_time` int NULL DEFAULT NULL,
  `delete_time` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '首页浏览记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_index_visit
-- ----------------------------

-- ----------------------------
-- Table structure for ai_invite_task_couple
-- ----------------------------
DROP TABLE IF EXISTS `ai_invite_task_couple`;
CREATE TABLE `ai_invite_task_couple`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `pid` int NULL DEFAULT NULL COMMENT '父ID',
  `inviting_user_id` int NULL DEFAULT NULL COMMENT '发起邀请用户ID',
  `invited_user_id` int NULL DEFAULT NULL COMMENT '被邀请用户ID',
  `tag_file_id` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '作图风格文件在上游系统的ID。多个用逗号分隔',
  `inviting_file_id` int NULL DEFAULT NULL COMMENT '发起邀请用户图片ID',
  `invited_file_id` int NULL DEFAULT NULL COMMENT '被邀请用户图片ID',
  `user_draft` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '用户作图的草稿信息。JSON格式',
  `status` tinyint NOT NULL DEFAULT 0 COMMENT '0=默认 1=进行中 2=已完成 3=已过期',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  `expire_time` int NULL DEFAULT NULL COMMENT '过期时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_uid`(`inviting_user_id` ASC) USING BTREE,
  INDEX `idx_uid_2`(`invited_user_id` ASC) USING BTREE,
  INDEX `idx_pid`(`pid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '双人合照任务表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_invite_task_couple
-- ----------------------------

-- ----------------------------
-- Table structure for ai_jobs
-- ----------------------------
DROP TABLE IF EXISTS `ai_jobs`;
CREATE TABLE `ai_jobs`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `attempts` mediumint UNSIGNED NOT NULL,
  `reserve_time` int UNSIGNED NULL DEFAULT NULL,
  `available_time` int UNSIGNED NOT NULL,
  `create_time` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `queue`(`queue` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5309527 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for ai_luna_drawing_task
-- ----------------------------
DROP TABLE IF EXISTS `ai_luna_drawing_task`;
CREATE TABLE `ai_luna_drawing_task`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int NOT NULL COMMENT '用户id',
  `order_sn` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '关联订单编号',
  `order_id` int NOT NULL DEFAULT 0 COMMENT '关联订单id',
  `up_task_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '上游任务ID',
  `draw_number` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '作图数量',
  `tag_file_id` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '作图风格文件在上游系统的ID。多个用逗号分隔',
  `user_file_id` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '用户上传图片在本系统的ID。多个用逗号分隔',
  `up_user_file_id` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '用户上传图片在上游系统的ID。多个用逗号分隔',
  `user_draft` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '用户作图的草稿信息。JSON格式',
  `upstream_resp` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '上游系统的返回。JSON格式',
  `result_images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '生成结果多张图片URL。JSON格式',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '任务状态：0=默认 1=处理中 2=成功 3=失败',
  `is_retry` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否是重试任务 1=是 0=不是',
  `error_msg` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '失败信息',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  `is_watermark` tinyint NOT NULL DEFAULT 0 COMMENT '是否带水印',
  `upstream_resp_thumb` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '缩略图-上游系统的返回。JSON格式',
  `result_images_thumb` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '缩略图-生成结果多张图片URL。JSON格式',
  `is_share` tinyint(1) NOT NULL DEFAULT 0,
  `share_task_id` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_user_order`(`user_id` ASC, `order_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8410 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'LUNA作图任务' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_luna_drawing_task
-- ----------------------------

-- ----------------------------
-- Table structure for ai_member_benefits
-- ----------------------------
DROP TABLE IF EXISTS `ai_member_benefits`;
CREATE TABLE `ai_member_benefits`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '权益名称',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '权益图标',
  `status` tinyint(1) NOT NULL COMMENT '状态：1-开启；0-关闭；',
  `sort` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员权益' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_member_benefits
-- ----------------------------

-- ----------------------------
-- Table structure for ai_member_order
-- ----------------------------
DROP TABLE IF EXISTS `ai_member_order`;
CREATE TABLE `ai_member_order`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int NOT NULL COMMENT '用户id',
  `sn` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '订单编号',
  `terminal` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '订单来源：1-微信小程序；2-微信公众号；3-手机H5；4-PC；5-苹果app；6-安卓app；',
  `pay_sn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '支付编号-冗余字段，针对微信同一主体不同客户端支付需用不同订单号预留。',
  `pay_way` tinyint NOT NULL DEFAULT 2 COMMENT '支付方式 2-微信支付 3-支付宝支付',
  `pay_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '支付状态：0-待支付；1-已支付',
  `pay_time` int NULL DEFAULT NULL COMMENT '支付时间',
  `order_amount` decimal(10, 2) UNSIGNED NOT NULL COMMENT '实付金额',
  `take_discount` tinyint NOT NULL DEFAULT 0 COMMENT '是否享受折扣：1=享受折扣价格 0=不打折',
  `discount_amount` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '优惠金额',
  `total_amount` decimal(10, 2) UNSIGNED NOT NULL COMMENT '订单总价',
  `transaction_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '第三方平台交易流水号',
  `member_package_id` int NULL DEFAULT NULL COMMENT '套餐ID',
  `member_package_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '套餐信息',
  `refund_status` tinyint(1) NULL DEFAULT 0 COMMENT '退款状态 0-未退款 1-已退款',
  `refund_transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '退款交易流水号',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  `apple_iap_verify_status` tinyint NOT NULL DEFAULT 0 COMMENT '苹果IAP支付校验状态：0=未校验,1=成功,2=失败',
  `apple_iap_verify_result` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '苹果IAP支付校验结果',
  `is_free` tinyint NOT NULL DEFAULT 0 COMMENT '是否免费试用',
  `origin_transaction_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '订阅原始第三方订单号',
  `origin_order_id` int NULL DEFAULT NULL COMMENT '订阅原始订单ID',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_uid`(`user_id` ASC) USING BTREE,
  INDEX `idx_sn`(`sn` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员订单表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_member_order
-- ----------------------------

-- ----------------------------
-- Table structure for ai_member_package
-- ----------------------------
DROP TABLE IF EXISTS `ai_member_package`;
CREATE TABLE `ai_member_package`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '套餐名称',
  `trial_name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '试用优惠名称',
  `duration` int UNSIGNED NOT NULL COMMENT '套餐时长',
  `duration_days` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '套餐时长天数',
  `is_perpetual` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否永久套餐：1-是；0-否',
  `sell_price` decimal(10, 2) UNSIGNED NOT NULL COMMENT '销售价格',
  `lineation_price` decimal(10, 2) UNSIGNED NULL DEFAULT NULL COMMENT '划线价',
  `is_retrieve` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否开启挽回优惠：1-开启；0-关闭；',
  `retrieve_amount` decimal(10, 2) NULL DEFAULT NULL COMMENT '挽回优惠金额',
  `sort` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否上架：1-上架；0-下架；',
  `is_default` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否默认：1-是；0-否；',
  `chat_limit` int UNSIGNED NULL DEFAULT NULL COMMENT '每日对话上限',
  `benefits_ids` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '会员权益',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  `period_draw_limit` int UNSIGNED NULL DEFAULT NULL COMMENT '周期内作图上限',
  `period_unit` int UNSIGNED NULL DEFAULT NULL COMMENT '周期单位。0=天，1=周，2=月，3=季度，4=年度',
  `product_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '苹果商品ID',
  `could_try` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否可以试用。1=可以 0=不可以',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员套餐' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_member_package
-- ----------------------------

-- ----------------------------
-- Table structure for ai_member_package_comment
-- ----------------------------
DROP TABLE IF EXISTS `ai_member_package_comment`;
CREATE TABLE `ai_member_package_comment`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '评价类型：1-虚拟评价；2-用户自评；',
  `member_package_id` int NOT NULL COMMENT '会员套餐ID',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '头像',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户昵称',
  `comment_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '评价内容',
  `comment_level` tinyint(1) NOT NULL COMMENT '评价等级：1-5星',
  `status` tinyint(1) NOT NULL COMMENT '状态：1-显示；0-隐藏；',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员套餐评价' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_member_package_comment
-- ----------------------------

-- ----------------------------
-- Table structure for ai_migrations
-- ----------------------------
DROP TABLE IF EXISTS `ai_migrations`;
CREATE TABLE `ai_migrations`  (
  `version` bigint NOT NULL,
  `migration_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `breakpoint` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`version`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_migrations
-- ----------------------------
INSERT INTO `ai_migrations` VALUES (20230802130419, 'CreateJobsTable', '2023-08-02 21:07:32', '2023-08-02 21:07:32', 0);
INSERT INTO `ai_migrations` VALUES (20230802130611, 'CreateFailedJobsTable', '2023-08-02 21:07:32', '2023-08-02 21:07:32', 0);

-- ----------------------------
-- Table structure for ai_notice_record
-- ----------------------------
DROP TABLE IF EXISTS `ai_notice_record`;
CREATE TABLE `ai_notice_record`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int UNSIGNED NOT NULL COMMENT '用户id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '内容',
  `scene_id` int UNSIGNED NULL DEFAULT 0 COMMENT '场景',
  `read` tinyint(1) NULL DEFAULT 0 COMMENT '已读状态;0-未读,1-已读',
  `recipient` tinyint(1) NULL DEFAULT 0 COMMENT '通知接收对象类型;1-会员;2-商家;3-平台;4-游客(未注册用户)',
  `send_type` tinyint(1) NULL DEFAULT 0 COMMENT '通知发送类型 1-系统通知 2-短信通知 3-微信模板 4-微信小程序',
  `notice_type` tinyint(1) NULL DEFAULT NULL COMMENT '通知类型 1-业务通知 2-验证码',
  `extra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '' COMMENT '其他',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 140 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '通知记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_notice_record
-- ----------------------------

-- ----------------------------
-- Table structure for ai_notice_setting
-- ----------------------------
DROP TABLE IF EXISTS `ai_notice_setting`;
CREATE TABLE `ai_notice_setting`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `scene_id` int NOT NULL COMMENT '场景id',
  `scene_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '场景名称',
  `scene_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '场景描述',
  `recipient` tinyint(1) NOT NULL DEFAULT 1 COMMENT '接收者 1-用户 2-平台',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '通知类型: 1-业务通知 2-验证码',
  `system_notice` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '系统通知设置',
  `sms_notice` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '短信通知设置',
  `oa_notice` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '公众号通知设置',
  `mnp_notice` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '小程序通知设置',
  `support` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '支持的发送类型 1-系统通知 2-短信通知 3-微信模板消息 4-小程序提醒',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '通知设置表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_notice_setting
-- ----------------------------
INSERT INTO `ai_notice_setting` VALUES (1, 101, '登录验证码', '用户手机号码登录时发送', 1, 2, '{\"type\":\"system\",\"title\":\"\",\"content\":\"\",\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\"]}', '{\"type\":\"sms\",\"template_id\":\"SMS_123456\",\"content\":\"You are currently attempting to login.<br><br>Verification Code: <b>${code}</b><br><br>Please do not share this verification code with anyone else. It is valid for 5 minutes only.<br>\",\"status\":\"1\",\"is_show\":\"1\"}', '{\"type\":\"oa\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"first\":\"\",\"remark\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\",\"配置路径：小程序后台 > 功能 > 订阅消息\"]}', '{\"type\":\"mnp\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\",\"配置路径：小程序后台 > 功能 > 订阅消息\"]}', '2', NULL);
INSERT INTO `ai_notice_setting` VALUES (2, 102, '绑定手机验证码', '用户绑定手机号码时发送', 1, 2, '{\"type\":\"system\",\"title\":\"\",\"content\":\"\",\"status\":\"0\",\"is_show\":\"\"}', '{\"type\":\"sms\",\"template_id\":\"SMS_123456\",\"content\":\"您正在绑定手机号，验证码${code}，切勿将验证码泄露于他人，本条验证码有效期5分钟。\",\"status\":\"1\",\"is_show\":\"1\"}', '{\"type\":\"oa\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"first\":\"\",\"remark\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\"}', '{\"type\":\"mnp\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\"}', '2', NULL);
INSERT INTO `ai_notice_setting` VALUES (3, 103, '变更手机验证码', '用户变更手机号码时发送', 1, 2, '{\"type\":\"system\",\"title\":\"\",\"content\":\"\",\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\"]}', '{\"type\":\"sms\",\"template_id\":\"SMS_123456\",\"content\":\"您正在变更手机号，验证码${code}，切勿将验证码泄露于他人，本条验证码有效期5分钟。\",\"status\":\"1\",\"is_show\":\"1\"}', '{\"type\":\"oa\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"first\":\"\",\"remark\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\",\"配置路径：小程序后台 > 功能 > 订阅消息\"]}', '{\"type\":\"mnp\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\",\"配置路径：小程序后台 > 功能 > 订阅消息\"]}', '2', NULL);
INSERT INTO `ai_notice_setting` VALUES (4, 104, '找回登录密码验证码', '用户找回登录密码号码时发送', 1, 2, '{\"type\":\"system\",\"title\":\"\",\"content\":\"\",\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\"]}', '{\"type\":\"sms\",\"template_id\":\"SMS_123456\",\"content\":\"您正在找回登录密码，验证码${code}，切勿将验证码泄露于他人，本条验证码有效期5分钟。\",\"status\":\"1\",\"is_show\":\"1\"}', '{\"type\":\"oa\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"first\":\"\",\"remark\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\",\"配置路径：小程序后台 > 功能 > 订阅消息\"]}', '{\"type\":\"mnp\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\",\"配置路径：小程序后台 > 功能 > 订阅消息\"]}', '2', NULL);

-- ----------------------------
-- Table structure for ai_operation_log
-- ----------------------------
DROP TABLE IF EXISTS `ai_operation_log`;
CREATE TABLE `ai_operation_log`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `admin_id` int NOT NULL COMMENT '管理员ID',
  `admin_name` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '管理员名称',
  `account` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '管理员账号',
  `action` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '操作名称',
  `type` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '请求方式',
  `url` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '访问链接',
  `params` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '请求数据',
  `result` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '请求结果',
  `ip` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'ip地址',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1644 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统日志表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_operation_log
-- ----------------------------

-- ----------------------------
-- Table structure for ai_recharge_order
-- ----------------------------
DROP TABLE IF EXISTS `ai_recharge_order`;
CREATE TABLE `ai_recharge_order`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int NOT NULL COMMENT '用户id',
  `sn` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '订单编号',
  `terminal` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '订单来源：1-微信小程序；2-微信公众号；3-手机H5；4-PC；5-苹果app；6-安卓app；',
  `pay_sn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '' COMMENT '支付编号-冗余字段，针对微信同一主体不同客户端支付需用不同订单号预留。',
  `pay_way` tinyint NOT NULL DEFAULT 2 COMMENT '支付方式 2-微信支付 3-支付宝支付',
  `pay_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '支付状态：0-待支付；1-已支付',
  `pay_time` int NULL DEFAULT NULL COMMENT '支付时间',
  `order_amount` decimal(10, 2) UNSIGNED NOT NULL COMMENT '实付金额',
  `take_discount` tinyint NOT NULL DEFAULT 0 COMMENT '是否享受折扣：1=享受折扣价格 0=不打折',
  `number` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '充值对话次数',
  `draw_number` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '充值绘画次数',
  `transaction_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '第三方平台交易流水号',
  `recharge_package_id` int NULL DEFAULT NULL COMMENT '充值套餐ID',
  `recharge_package_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '充值套餐信息',
  `refund_status` tinyint(1) NULL DEFAULT 0 COMMENT '退款状态 0-未退款 1-已退款',
  `refund_transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '退款交易流水号',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  `user_draft` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '用户作图的草稿信息。JSON格式',
  `apple_iap_verify_status` tinyint NOT NULL DEFAULT 0 COMMENT '苹果IAP支付校验状态：0=未校验,1=成功,2=失败',
  `apple_iap_verify_result` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '苹果IAP支付校验结果',
  `is_free` tinyint NOT NULL DEFAULT 0 COMMENT '是否无需付款',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_uid`(`user_id` ASC) USING BTREE,
  INDEX `idx_sn`(`sn` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '充值订单' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_recharge_order
-- ----------------------------

-- ----------------------------
-- Table structure for ai_recharge_package
-- ----------------------------
DROP TABLE IF EXISTS `ai_recharge_package`;
CREATE TABLE `ai_recharge_package`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `category` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '分类',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '套餐名称',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '套餐封面',
  `describe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '套餐描述',
  `sell_price` decimal(10, 2) UNSIGNED NOT NULL COMMENT '套餐价格',
  `draw_number` int UNSIGNED NOT NULL COMMENT '绘画次数',
  `is_give` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否开启赠送：1-开启；0-关闭；',
  `give_number` int UNSIGNED NULL DEFAULT 0 COMMENT '赠送对话次数',
  `give_draw_number` int UNSIGNED NULL DEFAULT 0 COMMENT '赠送绘画次数',
  `is_recommend` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否推荐：1-是；0-否；',
  `sort` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '套餐状态：1-开启；0-关闭；',
  `number` int UNSIGNED NOT NULL COMMENT '对话次数',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  `discount_price` decimal(10, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '折后价格',
  `currency` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '币种',
  `product_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '苹果商品ID',
  `show_discount_ratio` decimal(5, 2) NOT NULL DEFAULT 0.00 COMMENT '显示折扣比例',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_ctg`(`category` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '充值套餐' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_recharge_package
-- ----------------------------
INSERT INTO `ai_recharge_package` VALUES (7, 'portrait', '30张写真', '', '30张靓丽肖像', 6.90, 30, 0, 0, 0, 1, 100, 1, 0, 1683968723, 1683968723, NULL, 2.90, 'CNY', '', 0.00);
INSERT INTO `ai_recharge_package` VALUES (8, 'portrait', '15张写真', '', '15张靓丽肖像', 0.00, 15, 0, 0, 0, 0, 120, 1, 0, 1683968723, 1683968723, NULL, 0.00, 'CNY', '', 0.00);
INSERT INTO `ai_recharge_package` VALUES (10, 'gallary', '15张写真', '', '15张剧中造型', 49.90, 15, 0, 0, 0, 1, 100, 1, 0, 1683968723, 1683968723, NULL, 24.90, 'CNY', '', 0.00);
INSERT INTO `ai_recharge_package` VALUES (11, 'gallary', '4张写真', '', '4张剧中造型', 17.90, 4, 0, 0, 0, 0, 80, 1, 0, 1683968723, 1683968723, NULL, 9.90, 'CNY', '', 0.00);

-- ----------------------------
-- Table structure for ai_sms_log
-- ----------------------------
DROP TABLE IF EXISTS `ai_sms_log`;
CREATE TABLE `ai_sms_log`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'id',
  `scene_id` int NOT NULL COMMENT '场景id',
  `mobile` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '手机号码或邮箱',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '发送内容',
  `code` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '发送关键字（注册、找回密码）',
  `is_verify` tinyint(1) NULL DEFAULT 0 COMMENT '是否已验证；0-否；1-是',
  `check_num` int NULL DEFAULT 0 COMMENT '验证次数',
  `send_status` tinyint(1) NOT NULL COMMENT '发送状态：0-发送中；1-发送成功；2-发送失败',
  `send_time` int NOT NULL COMMENT '发送时间',
  `results` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '短信结果',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '短信记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_sms_log
-- ----------------------------

-- ----------------------------
-- Table structure for ai_system_menu
-- ----------------------------
DROP TABLE IF EXISTS `ai_system_menu`;
CREATE TABLE `ai_system_menu`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `pid` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '上级菜单',
  `type` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '权限类型: M=目录，C=菜单，A=按钮',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单图标',
  `sort` smallint UNSIGNED NOT NULL DEFAULT 0 COMMENT '菜单排序',
  `perms` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '权限标识',
  `paths` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '路由地址',
  `component` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '前端组件',
  `selected` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '选中路径',
  `params` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '路由参数',
  `is_cache` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否缓存: 0=否, 1=是',
  `is_show` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否显示: 0=否, 1=是',
  `is_disable` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否禁用: 0=否, 1=是',
  `create_time` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 334 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统菜单表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_system_menu
-- ----------------------------
INSERT INTO `ai_system_menu` VALUES (4, 0, 'M', '权限管理', 'el-icon-Lock', 400, '', 'permission', '', '', '', 0, 1, 0, 1656664556, 1664354975);
INSERT INTO `ai_system_menu` VALUES (5, 0, 'C', '工作台', 'el-icon-Monitor', 1000, 'workbench/index', 'workbench', 'workbench/index', '', '', 0, 1, 0, 1656664793, 1664354981);
INSERT INTO `ai_system_menu` VALUES (6, 4, 'C', '菜单', '', 1, 'auth.menu/lists', 'menu', 'permission/menu/index', '', '', 1, 1, 0, 1656664960, 1682492477);
INSERT INTO `ai_system_menu` VALUES (7, 4, 'C', '管理员', '', 1, 'auth.admin/lists', 'admin', 'permission/admin/index', '', '', 0, 1, 0, 1656901567, 1682492485);
INSERT INTO `ai_system_menu` VALUES (8, 4, 'C', '角色', '', 1, 'auth.role/lists', 'role', 'permission/role/index', '', '', 0, 1, 0, 1656901660, 1682492493);
INSERT INTO `ai_system_menu` VALUES (12, 8, 'A', '新增', '', 1, 'auth.role/add', '', '', '', '', 0, 1, 0, 1657001790, 1663750625);
INSERT INTO `ai_system_menu` VALUES (14, 8, 'A', '编辑', '', 1, 'auth.role/edit', '', '', '', '', 0, 1, 0, 1657001924, 1663750631);
INSERT INTO `ai_system_menu` VALUES (15, 8, 'A', '删除', '', 1, 'auth.role/delete', '', '', '', '', 0, 1, 0, 1657001982, 1663750637);
INSERT INTO `ai_system_menu` VALUES (16, 6, 'A', '新增', '', 1, 'auth.menu/add', '', '', '', '', 0, 1, 0, 1657072523, 1663750565);
INSERT INTO `ai_system_menu` VALUES (17, 6, 'A', '编辑', '', 1, 'auth.menu/edit', '', '', '', '', 0, 1, 0, 1657073955, 1663750570);
INSERT INTO `ai_system_menu` VALUES (18, 6, 'A', '删除', '', 1, 'auth.menu/delete', '', '', '', '', 0, 1, 0, 1657073987, 1663750578);
INSERT INTO `ai_system_menu` VALUES (19, 7, 'A', '新增', '', 1, 'auth.admin/add', '', '', '', '', 0, 1, 0, 1657074035, 1663750596);
INSERT INTO `ai_system_menu` VALUES (20, 7, 'A', '编辑', '', 1, 'auth.admin/edit', '', '', '', '', 0, 1, 0, 1657074071, 1663750603);
INSERT INTO `ai_system_menu` VALUES (21, 7, 'A', '删除', '', 1, 'auth.admin/delete', '', '', '', '', 0, 1, 0, 1657074108, 1663750609);
INSERT INTO `ai_system_menu` VALUES (23, 0, 'M', '开发工具', 'el-icon-EditPen', 100, '', 'dev_tools', '', '', '', 0, 1, 0, 1657097744, 1699323621);
INSERT INTO `ai_system_menu` VALUES (24, 23, 'C', '代码生成器', 'el-icon-DocumentAdd', 1, 'tools.generator/generateTable', 'code', 'dev_tools/code/index', '', '', 0, 1, 0, 1657098110, 1658989423);
INSERT INTO `ai_system_menu` VALUES (25, 0, 'M', '组织管理', 'el-icon-OfficeBuilding', 500, '', 'organization', '', '', '', 0, 1, 0, 1657099914, 1717919792);
INSERT INTO `ai_system_menu` VALUES (26, 25, 'C', '部门管理', 'el-icon-Coordinate', 1, 'dept.dept/lists', 'department', 'organization/department/index', '', '', 1, 1, 0, 1657099989, 1664353662);
INSERT INTO `ai_system_menu` VALUES (27, 25, 'C', '岗位管理', 'el-icon-PriceTag', 1, 'dept.jobs/lists', 'post', 'organization/post/index', '', '', 0, 1, 0, 1657100044, 1658989726);
INSERT INTO `ai_system_menu` VALUES (28, 0, 'M', '系统设置', 'el-icon-Setting', 200, '', 'setting', '', '', '', 0, 1, 0, 1657100164, 1664355035);
INSERT INTO `ai_system_menu` VALUES (29, 28, 'M', '网站设置', '', 100, '', 'website', '', '', '', 0, 1, 0, 1657100230, 1682492512);
INSERT INTO `ai_system_menu` VALUES (30, 29, 'C', '网站信息', '', 1, 'setting.web.web_setting/getWebsite', 'information', 'setting/website/information', '', '', 0, 1, 0, 1657100306, 1657164412);
INSERT INTO `ai_system_menu` VALUES (31, 29, 'C', '网站备案', '', 1, 'setting.web.web_setting/getCopyright', 'filing', 'setting/website/filing', '', '', 0, 1, 0, 1657100434, 1657164723);
INSERT INTO `ai_system_menu` VALUES (32, 29, 'C', '政策协议', '', 1, 'setting.web.web_setting/getAgreement', 'protocol', 'setting/website/protocol', '', '', 0, 1, 0, 1657100571, 1657164770);
INSERT INTO `ai_system_menu` VALUES (33, 28, 'C', '存储设置', '', 1, 'setting.storage/lists', 'storage', 'setting/storage/index', '', '', 0, 1, 0, 1657160959, 1682492584);
INSERT INTO `ai_system_menu` VALUES (34, 23, 'C', '字典管理', 'el-icon-Box', 1, 'setting.dict.dict_type/lists', 'dict', 'setting/dict/type/index', '', '', 0, 1, 0, 1657161211, 1663225935);
INSERT INTO `ai_system_menu` VALUES (35, 28, 'M', '系统维护', '', 1, '', 'system', '', '', '', 0, 1, 0, 1657161569, 1682492592);
INSERT INTO `ai_system_menu` VALUES (36, 35, 'C', '系统日志', '', 1, 'setting.system.log/lists', 'journal', 'setting/system/journal', '', '', 0, 1, 0, 1657161696, 1657165722);
INSERT INTO `ai_system_menu` VALUES (37, 35, 'C', '系统缓存', '', 1, '', 'cache', 'setting/system/cache', '', '', 0, 1, 0, 1657161896, 1657173767);
INSERT INTO `ai_system_menu` VALUES (38, 35, 'C', '系统环境', '', 1, 'setting.system.system/info', 'environment', 'setting/system/environment', '', '', 0, 1, 0, 1657162000, 1657173794);
INSERT INTO `ai_system_menu` VALUES (39, 24, 'A', '导入数据表', '', 1, 'tools.generator/selectTable', '', '', '', '', 0, 1, 0, 1657162736, 1657162736);
INSERT INTO `ai_system_menu` VALUES (40, 24, 'A', '代码生成', '', 1, 'tools.generator/generate', '', '', '', '', 0, 1, 0, 1657162806, 1657162806);
INSERT INTO `ai_system_menu` VALUES (41, 23, 'C', '编辑数据表', '', 1, 'tools.generator/edit', 'code/edit', 'dev_tools/code/edit', '/dev_tools/code', '', 1, 0, 0, 1657162866, 1663748668);
INSERT INTO `ai_system_menu` VALUES (42, 24, 'A', '同步表结构', '', 1, 'tools.generator/syncColumn', '', '', '', '', 0, 1, 0, 1657162934, 1657162934);
INSERT INTO `ai_system_menu` VALUES (43, 24, 'A', '删除数据表', '', 1, 'tools.generator/delete', '', '', '', '', 0, 1, 0, 1657163015, 1657163015);
INSERT INTO `ai_system_menu` VALUES (44, 24, 'A', '预览代码', '', 1, 'tools.generator/preview', '', '', '', '', 0, 1, 0, 1657163263, 1657163263);
INSERT INTO `ai_system_menu` VALUES (45, 26, 'A', '新增', '', 1, 'dept.dept/add', '', '', '', '', 0, 1, 0, 1657163548, 1663750492);
INSERT INTO `ai_system_menu` VALUES (46, 26, 'A', '编辑', '', 1, 'dept.dept/edit', '', '', '', '', 0, 1, 0, 1657163599, 1663750498);
INSERT INTO `ai_system_menu` VALUES (47, 26, 'A', '删除', '', 1, 'dept.dept/delete', '', '', '', '', 0, 1, 0, 1657163687, 1663750504);
INSERT INTO `ai_system_menu` VALUES (48, 27, 'A', '新增', '', 1, 'dept.jobs/add', '', '', '', '', 0, 1, 0, 1657163778, 1663750524);
INSERT INTO `ai_system_menu` VALUES (49, 27, 'A', '编辑', '', 1, 'dept.jobs/edit', '', '', '', '', 0, 1, 0, 1657163800, 1663750530);
INSERT INTO `ai_system_menu` VALUES (50, 27, 'A', '删除', '', 1, 'dept.jobs/delete', '', '', '', '', 0, 1, 0, 1657163820, 1663750535);
INSERT INTO `ai_system_menu` VALUES (51, 30, 'A', '保存', '', 1, 'setting.web.web_setting/setWebsite', '', '', '', '', 0, 1, 0, 1657164469, 1663750649);
INSERT INTO `ai_system_menu` VALUES (52, 31, 'A', '保存', '', 1, 'setting.web.web_setting/setCopyright', '', '', '', '', 0, 1, 0, 1657164692, 1663750657);
INSERT INTO `ai_system_menu` VALUES (53, 32, 'A', '保存', '', 1, 'setting.web.web_setting/setAgreement', '', '', '', '', 0, 1, 0, 1657164824, 1663750665);
INSERT INTO `ai_system_menu` VALUES (54, 33, 'A', '设置', '', 1, 'setting.storage/setup', '', '', '', '', 0, 1, 0, 1657165303, 1663750673);
INSERT INTO `ai_system_menu` VALUES (55, 34, 'A', '新增', '', 1, 'setting.dict.dict_type/add', '', '', '', '', 0, 1, 0, 1657166966, 1663750783);
INSERT INTO `ai_system_menu` VALUES (56, 34, 'A', '编辑', '', 1, 'setting.dict.dict_type/edit', '', '', '', '', 0, 1, 0, 1657166997, 1663750789);
INSERT INTO `ai_system_menu` VALUES (57, 34, 'A', '删除', '', 1, 'setting.dict.dict_type/delete', '', '', '', '', 0, 1, 0, 1657167038, 1663750796);
INSERT INTO `ai_system_menu` VALUES (58, 62, 'A', '新增', '', 1, 'setting.dict.dict_data/add', '', '', '', '', 0, 1, 0, 1657167317, 1663750758);
INSERT INTO `ai_system_menu` VALUES (59, 62, 'A', '编辑', '', 1, 'setting.dict.dict_data/edit', '', '', '', '', 0, 1, 0, 1657167371, 1663750751);
INSERT INTO `ai_system_menu` VALUES (60, 62, 'A', '删除', '', 1, 'setting.dict.dict_data/delete', '', '', '', '', 0, 1, 0, 1657167397, 1663750768);
INSERT INTO `ai_system_menu` VALUES (61, 37, 'A', '清除系统缓存', '', 1, 'setting.system.cache/clear', '', '', '', '', 0, 1, 0, 1657173837, 1657173939);
INSERT INTO `ai_system_menu` VALUES (62, 23, 'C', '字典数据管理', '', 1, 'setting.dict.dict_data/lists', 'dict/data', 'setting/dict/data/index', '/dev_tools/dict', '', 1, 0, 0, 1657174351, 1663745617);
INSERT INTO `ai_system_menu` VALUES (63, 0, 'M', '素材管理', 'el-icon-Picture', 300, '', 'material', '', '', '', 0, 1, 1, 1657507133, 1721776521);
INSERT INTO `ai_system_menu` VALUES (64, 96, 'C', '素材中心', '', 60, '', 'index', 'material/index', '', '', 0, 1, 0, 1657507296, 1682492067);
INSERT INTO `ai_system_menu` VALUES (66, 26, 'A', '详情', '', 0, 'dept.dept/detail', '', '', '', '', 0, 1, 0, 1663725459, 1663750516);
INSERT INTO `ai_system_menu` VALUES (67, 27, 'A', '详情', '', 0, 'dept.jobs/detail', '', '', '', '', 0, 1, 0, 1663725514, 1663750559);
INSERT INTO `ai_system_menu` VALUES (68, 6, 'A', '详情', '', 0, 'auth.menu/detail', '', '', '', '', 0, 1, 0, 1663725564, 1663750584);
INSERT INTO `ai_system_menu` VALUES (69, 7, 'A', '详情', '', 0, 'auth.admin/detail', '', '', '', '', 0, 1, 0, 1663725623, 1663750615);
INSERT INTO `ai_system_menu` VALUES (70, 224, 'M', '文章资讯', '', 45, '', 'article', '', '', '', 0, 1, 0, 1663749965, 1687414509);
INSERT INTO `ai_system_menu` VALUES (71, 70, 'C', '文章管理', '', 0, 'article.article/lists', 'lists', 'app/article/lists/index', '', '', 0, 1, 0, 1663750101, 1687688036);
INSERT INTO `ai_system_menu` VALUES (72, 70, 'C', '文章添加/编辑', '', 0, 'article.article/add:edit', 'lists/edit', 'app/article/lists/edit', '/yingyong/article/lists', '', 0, 0, 0, 1663750153, 1687688249);
INSERT INTO `ai_system_menu` VALUES (73, 70, 'C', '文章栏目', '', 0, 'article.articleCate/lists', 'column', 'app/article/column/index', '', '', 1, 1, 0, 1663750287, 1687688042);
INSERT INTO `ai_system_menu` VALUES (74, 71, 'A', '新增', '', 0, 'article.article/add', '', '', '', '', 0, 1, 0, 1663750335, 1663750335);
INSERT INTO `ai_system_menu` VALUES (75, 71, 'A', '详情', '', 0, 'article.article/detail', '', '', '', '', 0, 1, 0, 1663750354, 1663750383);
INSERT INTO `ai_system_menu` VALUES (76, 71, 'A', '删除', '', 0, 'article.article/delete', '', '', '', '', 0, 1, 0, 1663750413, 1663750413);
INSERT INTO `ai_system_menu` VALUES (77, 71, 'A', '修改状态', '', 0, 'article.article/updateStatus', '', '', '', '', 0, 1, 0, 1663750442, 1663750442);
INSERT INTO `ai_system_menu` VALUES (78, 73, 'A', '添加', '', 0, 'article.articleCate/add', '', '', '', '', 0, 1, 0, 1663750483, 1663750483);
INSERT INTO `ai_system_menu` VALUES (79, 73, 'A', '删除', '', 0, 'article.articleCate/delete', '', '', '', '', 0, 1, 0, 1663750895, 1663750895);
INSERT INTO `ai_system_menu` VALUES (80, 73, 'A', '详情', '', 0, 'article.articleCate/detail', '', '', '', '', 0, 1, 0, 1663750913, 1663750913);
INSERT INTO `ai_system_menu` VALUES (81, 73, 'A', '修改状态', '', 0, 'article.articleCate/updateStatus', '', '', '', '', 0, 1, 0, 1663750936, 1663750936);
INSERT INTO `ai_system_menu` VALUES (82, 0, 'M', '渠道设置', 'el-icon-Message', 600, '', 'channel', '', '', '', 0, 1, 0, 1663754084, 1721778032);
INSERT INTO `ai_system_menu` VALUES (83, 82, 'C', 'h5设置', '', 0, 'channel.web_page_setting/getConfig', 'h5', 'channel/h5', '', '', 0, 1, 1, 1663754158, 1721778116);
INSERT INTO `ai_system_menu` VALUES (84, 83, 'A', '保存', '', 0, 'channel.web_page_setting/setConfig', '', '', '', '', 0, 1, 0, 1663754259, 1663754259);
INSERT INTO `ai_system_menu` VALUES (85, 82, 'M', '微信公众号', '', 0, '', 'wx_oa', '', '', '', 0, 1, 0, 1663755470, 1682492436);
INSERT INTO `ai_system_menu` VALUES (86, 85, 'C', '公众号配置', '', 0, 'channel.official_account_setting/getConfig', 'config', 'channel/wx_oa/config', '', '', 0, 1, 0, 1663755663, 1664355450);
INSERT INTO `ai_system_menu` VALUES (87, 85, 'C', '菜单管理', '', 0, 'channel.official_account_menu/detail', 'menu', 'channel/wx_oa/menu', '', '', 0, 1, 0, 1663755767, 1664355456);
INSERT INTO `ai_system_menu` VALUES (88, 86, 'A', '保存', '', 0, 'channel.official_account_setting/setConfig', '', '', '', '', 0, 1, 0, 1663755799, 1663755799);
INSERT INTO `ai_system_menu` VALUES (89, 86, 'A', '保存并发布', '', 0, 'channel.official_account_menu/save', '', '', '', '', 0, 1, 0, 1663756490, 1663756490);
INSERT INTO `ai_system_menu` VALUES (90, 85, 'C', '关注回复', '', 0, 'channel.official_account_reply/lists', 'follow', 'channel/wx_oa/reply/follow_reply', '', '', 0, 1, 0, 1663818358, 1663818366);
INSERT INTO `ai_system_menu` VALUES (91, 85, 'C', '关键字回复', '', 0, '', 'keyword', 'channel/wx_oa/reply/keyword_reply', '', '', 0, 1, 0, 1663818445, 1663818445);
INSERT INTO `ai_system_menu` VALUES (93, 85, 'C', '默认回复', '', 0, '', 'default', 'channel/wx_oa/reply/default_reply', '', '', 0, 1, 0, 1663818580, 1663818580);
INSERT INTO `ai_system_menu` VALUES (94, 82, 'C', '微信小程序', '', 1, 'channel.mnp_settings/getConfig', 'weapp', 'channel/weapp', '', '', 0, 1, 0, 1663831396, 1721778153);
INSERT INTO `ai_system_menu` VALUES (95, 94, 'A', '保存', '', 0, 'channel.mnp_settings/setConfig', '', '', '', '', 0, 1, 0, 1663831436, 1663831436);
INSERT INTO `ai_system_menu` VALUES (96, 0, 'M', '装修管理', 'el-icon-Brush', 700, '', 'decoration', '', '', '', 0, 1, 1, 1663834825, 1721777887);
INSERT INTO `ai_system_menu` VALUES (97, 96, 'C', '页面装修', '', 100, 'decorate.page/detail', 'pages', 'decoration/pages/index', '', '', 0, 1, 0, 1663834879, 1682492024);
INSERT INTO `ai_system_menu` VALUES (98, 97, 'A', '保存', '', 0, 'decorate.page/save', '', '', '', '', 0, 1, 0, 1663834956, 1663834956);
INSERT INTO `ai_system_menu` VALUES (99, 96, 'C', '导航菜单', '', 90, 'decorate.tabbar/detail', 'tabbar', 'decoration/tabbar', '', '', 0, 1, 0, 1663835004, 1688523827);
INSERT INTO `ai_system_menu` VALUES (100, 99, 'A', '保存', '', 0, 'decorate.tabbar/save', '', '', '', '', 0, 1, 0, 1663835018, 1663835018);
INSERT INTO `ai_system_menu` VALUES (101, 224, 'M', '消息管理', '', 40, '', 'message', '', '', '', 0, 1, 0, 1663838602, 1686909589);
INSERT INTO `ai_system_menu` VALUES (102, 101, 'C', '通知设置', '', 0, 'notice.notice/settingLists', 'notice', 'message/notice/index', '', '', 0, 1, 0, 1663839195, 1663839195);
INSERT INTO `ai_system_menu` VALUES (103, 102, 'A', '详情', '', 0, 'notice.notice/detail', '', '', '', '', 0, 1, 0, 1663839537, 1663839537);
INSERT INTO `ai_system_menu` VALUES (104, 101, 'C', '通知设置编辑', '', 0, 'notice.notice/set', 'notice/edit', 'message/notice/edit', '/message/notice', '', 0, 0, 0, 1663839873, 1663898477);
INSERT INTO `ai_system_menu` VALUES (105, 71, 'A', '编辑', '', 0, 'article.article/edit', '', '', '', '', 0, 1, 0, 1663840043, 1663840053);
INSERT INTO `ai_system_menu` VALUES (106, 71, 'A', '详情', '', 0, 'article.article/detail', '', '', '', '', 0, 1, 0, 1663840284, 1663840494);
INSERT INTO `ai_system_menu` VALUES (107, 101, 'C', '短信设置', '', 0, 'notice.sms_config/getConfig', 'short_letter', 'message/short_letter/index', '', '', 0, 1, 0, 1663898591, 1664355708);
INSERT INTO `ai_system_menu` VALUES (108, 107, 'A', '设置', '', 0, 'notice.sms_config/setConfig', '', '', '', '', 0, 1, 0, 1663898644, 1663898644);
INSERT INTO `ai_system_menu` VALUES (109, 107, 'A', '详情', '', 0, 'notice.sms_config/detail', '', '', '', '', 0, 1, 0, 1663898661, 1663898661);
INSERT INTO `ai_system_menu` VALUES (112, 28, 'M', '用户设置', '', 70, '', 'user', '', '', '', 0, 0, 1, 1663903302, 1685340748);
INSERT INTO `ai_system_menu` VALUES (113, 28, 'C', '用户设置', '', 58, 'setting.user.user/getConfig', 'setup', 'setting/user/setup', '', '', 0, 1, 0, 1663903506, 1685340850);
INSERT INTO `ai_system_menu` VALUES (114, 113, 'A', '保存', '', 0, 'setting.user.user/setConfig', '', '', '', '', 0, 1, 0, 1663903522, 1663903522);
INSERT INTO `ai_system_menu` VALUES (115, 28, 'C', '登录注册', '', 70, 'setting.user.user/getRegisterConfig', 'login_register', 'setting/user/login_register', '', '', 0, 1, 0, 1663903832, 1685340686);
INSERT INTO `ai_system_menu` VALUES (116, 115, 'A', '保存', '', 0, 'setting.user.user/setRegisterConfig', '', '', '', '', 0, 1, 0, 1663903852, 1663903852);
INSERT INTO `ai_system_menu` VALUES (117, 0, 'M', '用户管理', 'el-icon-User', 800, '', 'consumer', '', '', '', 0, 1, 0, 1663904351, 1664354732);
INSERT INTO `ai_system_menu` VALUES (118, 117, 'C', '用户列表', '', 0, 'user.user/lists', 'lists', 'consumer/lists/index', '', '', 0, 1, 0, 1663904392, 1682491998);
INSERT INTO `ai_system_menu` VALUES (119, 117, 'C', '用户详情', '', 0, 'user.user/detail', 'lists/detail', 'consumer/lists/detail', '/consumer/lists', '', 0, 0, 0, 1663904470, 1663928109);
INSERT INTO `ai_system_menu` VALUES (120, 119, 'A', '编辑', '', 0, 'user.user/edit', '', '', '', '', 0, 1, 0, 1663904499, 1663904499);
INSERT INTO `ai_system_menu` VALUES (140, 82, 'C', '微信开放平台', '', 0, 'channel.open_setting/getConfig', 'open_setting', 'channel/open_setting', '', '', 0, 1, 0, 1666085713, 1688454034);
INSERT INTO `ai_system_menu` VALUES (141, 140, 'A', '保存', '', 0, 'channel.open_setting/setConfig', '', '', '', '', 0, 1, 0, 1666085751, 1666085776);
INSERT INTO `ai_system_menu` VALUES (142, 96, 'C', 'PC端', '', 80, '', 'pc', 'decoration/pc', '', '', 0, 0, 1, 1668423284, 1682496759);
INSERT INTO `ai_system_menu` VALUES (143, 35, 'C', '定时任务', '', 0, 'crontab.crontab/lists', 'scheduled_task', 'setting/system/scheduled_task/index', '', '', 0, 1, 0, 1669357509, 1669357711);
INSERT INTO `ai_system_menu` VALUES (144, 35, 'C', '定时任务添加/编辑', '', 0, 'crontab.crontab/add:edit', 'scheduled_task/edit', 'setting/system/scheduled_task/edit', '/setting/system/scheduled_task', '', 0, 0, 0, 1669357670, 1669357765);
INSERT INTO `ai_system_menu` VALUES (145, 143, 'A', '添加', '', 0, 'crontab.crontab/add', '', '', '', '', 0, 1, 0, 1669358282, 1669358282);
INSERT INTO `ai_system_menu` VALUES (146, 143, 'A', '编辑', '', 0, 'crontab.crontab/edit', '', '', '', '', 0, 1, 0, 1669358303, 1669358303);
INSERT INTO `ai_system_menu` VALUES (147, 143, 'A', '删除', '', 0, 'crontab.crontab/delete', '', '', '', '', 0, 1, 0, 1669358334, 1669358334);
INSERT INTO `ai_system_menu` VALUES (148, 0, 'M', '模板示例', 'el-icon-SetUp', 0, '', 'template', '', '', '', 0, 0, 1, 1670206819, 1682491605);
INSERT INTO `ai_system_menu` VALUES (149, 148, 'M', '组件示例', 'el-icon-Coin', 0, '', 'component', '', '', '', 0, 1, 0, 1670207182, 1670207244);
INSERT INTO `ai_system_menu` VALUES (150, 149, 'C', '富文本', '', 0, '', 'rich_text', 'template/component/rich_text', '', '', 0, 1, 0, 1670207751, 1670207751);
INSERT INTO `ai_system_menu` VALUES (151, 149, 'C', '上传文件', '', 0, '', 'upload', 'template/component/upload', '', '', 0, 1, 0, 1670208925, 1670208925);
INSERT INTO `ai_system_menu` VALUES (152, 149, 'C', '图标', '', 0, '', 'icon', 'template/component/icon', '', '', 0, 1, 0, 1670230069, 1670230069);
INSERT INTO `ai_system_menu` VALUES (153, 149, 'C', '文件选择器', '', 0, '', 'file', 'template/component/file', '', '', 0, 1, 0, 1670232129, 1670232129);
INSERT INTO `ai_system_menu` VALUES (154, 149, 'C', '链接选择器', '', 0, '', 'link', 'template/component/link', '', '', 0, 1, 0, 1670292636, 1670292636);
INSERT INTO `ai_system_menu` VALUES (155, 149, 'C', '超出自动打点', '', 0, '', 'overflow', 'template/component/overflow', '', '', 0, 1, 0, 1670292883, 1670292883);
INSERT INTO `ai_system_menu` VALUES (156, 149, 'C', '悬浮input', '', 0, '', 'popover_input', 'template/component/popover_input', '', '', 0, 1, 0, 1670293336, 1670293336);
INSERT INTO `ai_system_menu` VALUES (157, 119, 'A', '余额调整', '', 0, 'user.user/adjustMoney', '', '', '', '', 0, 1, 0, 1677143088, 1677143088);
INSERT INTO `ai_system_menu` VALUES (158, 0, 'M', '应用管理', 'el-icon-Postcard', 750, '', 'app', '', '', '', 0, 0, 1, 1677143430, 1682491869);
INSERT INTO `ai_system_menu` VALUES (159, 224, 'C', '用户充值', 'local-icon-fukuan', 60, 'recharge.recharge/getConfig', 'recharge', 'app/recharge/index', '', '', 0, 0, 1, 1677144284, 1685329917);
INSERT INTO `ai_system_menu` VALUES (160, 159, 'A', '保存', '', 0, 'recharge.recharge/setConfig', '', '', '', '', 0, 1, 0, 1677145012, 1677145012);
INSERT INTO `ai_system_menu` VALUES (161, 28, 'M', '支付设置', '', 60, '', 'pay', '', '', '', 0, 1, 0, 1677148075, 1682492565);
INSERT INTO `ai_system_menu` VALUES (162, 161, 'C', '支付方式', '', 0, 'setting.pay.pay_way/getPayWay', 'method', 'setting/pay/method/index', '', '', 0, 1, 0, 1677148207, 1677148207);
INSERT INTO `ai_system_menu` VALUES (163, 161, 'C', '支付配置', '', 0, 'setting.pay.pay_config/lists', 'config', 'setting/pay/config/index', '', '', 0, 1, 0, 1677148260, 1677148374);
INSERT INTO `ai_system_menu` VALUES (164, 162, 'A', '设置支付方式', '', 0, 'setting.pay.pay_way/setPayWay', '', '', '', '', 0, 1, 0, 1677219624, 1677219624);
INSERT INTO `ai_system_menu` VALUES (165, 163, 'A', '配置', '', 0, 'setting.pay.pay_config/setConfig', '', '', '', '', 0, 1, 0, 1677219655, 1677219655);
INSERT INTO `ai_system_menu` VALUES (166, 0, 'M', '财务管理', 'local-icon-user_gaikuang', 650, '', 'finance', '', '', '', 0, 1, 0, 1677552269, 1677842158);
INSERT INTO `ai_system_menu` VALUES (170, 166, 'C', '退款记录', '', 0, 'finance.refund/record', 'refund_record', 'finance/refund_record', '', '', 0, 1, 0, 1677811271, 1685332920);
INSERT INTO `ai_system_menu` VALUES (171, 170, 'A', '重新退款', '', 0, 'finance.refund/refundAgain', '', '', '', '', 0, 1, 0, 1677811295, 1682581650);
INSERT INTO `ai_system_menu` VALUES (172, 170, 'A', '退款日志', '', 0, 'finance.refund/log', '', '', '', '', 0, 1, 0, 1677811361, 1677811361);
INSERT INTO `ai_system_menu` VALUES (173, 0, 'M', '营销中心', 'el-icon-Briefcase', 690, '', 'marketing', '', '', '', 0, 1, 0, 1681293663, 1685329871);
INSERT INTO `ai_system_menu` VALUES (174, 173, 'M', '分享奖励', '', 100, '', 'share', '', '', '', 0, 1, 0, 1681293726, 1682492111);
INSERT INTO `ai_system_menu` VALUES (175, 174, 'C', '分享设置', '', 0, 'task.task_share/getConfig', 'sharesetting', 'marketing/share/setting', '', '', 0, 1, 0, 1681293866, 1686725641);
INSERT INTO `ai_system_menu` VALUES (176, 174, 'C', '分享记录', '', 0, 'task.task_share/lists', 'sharerecord', 'marketing/share/record', '', '', 0, 1, 0, 1681293969, 1686725735);
INSERT INTO `ai_system_menu` VALUES (177, 173, 'M', '邀请奖励', '', 80, '', 'invite', '', '', '', 0, 1, 0, 1681295043, 1682492207);
INSERT INTO `ai_system_menu` VALUES (178, 177, 'C', '邀请奖励', '', 0, 'task.task_invite/getConfig', 'invitesetting', 'marketing/invite/setting', '', '', 0, 1, 0, 1681295079, 1686725759);
INSERT INTO `ai_system_menu` VALUES (179, 177, 'C', '邀请记录', '', 0, 'task.task_invite/lists', 'inviterecord', 'marketing/invite/record', '', '', 0, 1, 0, 1681295110, 1686725815);
INSERT INTO `ai_system_menu` VALUES (180, 173, 'C', '充值套餐', '', 70, 'recharge.recharge_package/lists', 'recharge', 'marketing/recharge/index', '', '', 0, 1, 0, 1681295905, 1687941071);
INSERT INTO `ai_system_menu` VALUES (183, 173, 'M', '会员套餐', '', 60, '', 'vipcombo', '', '', '', 0, 1, 0, 1681296406, 1682492298);
INSERT INTO `ai_system_menu` VALUES (184, 183, 'C', '会员套餐', '', 0, 'member.member_package/lists', 'combo', 'marketing/vip_combo/vip_combo', '', '', 0, 1, 0, 1681296429, 1686725928);
INSERT INTO `ai_system_menu` VALUES (187, 0, 'M', '订单管理', 'el-icon-Coin', 920, '', 'order', '', '', '', 0, 1, 0, 1681354941, 1682491723);
INSERT INTO `ai_system_menu` VALUES (188, 187, 'C', '充值订单', '', 0, 'recharge.recharge_order/lists', 'rechargeorder', 'order/recharge_order/lists', '', '', 0, 1, 0, 1681355001, 1686724726);
INSERT INTO `ai_system_menu` VALUES (192, 187, 'C', '会员订单', '', 100, 'member.member_order/lists', 'viporder', 'order/vip_order/lists', '', '', 0, 1, 0, 1681366476, 1688020227);
INSERT INTO `ai_system_menu` VALUES (193, 82, 'C', 'pc设置', '', 0, '', 'pc', 'channel/pc', '', '', 0, 1, 1, 1681367803, 1721778122);
INSERT INTO `ai_system_menu` VALUES (198, 183, 'C', '购买评价', '', 0, 'member.member_package_comment/lists', 'evaluate', 'marketing/vip_combo/evaluate/lists', '', '', 0, 1, 0, 1681437201, 1686726358);
INSERT INTO `ai_system_menu` VALUES (202, 28, 'C', '分享设置', '', 70, 'setting.shareSetting/getConfig', 'share', 'setting/share/index', '', '', 0, 1, 0, 1681608049, 1686729660);
INSERT INTO `ai_system_menu` VALUES (204, 166, 'C', '财务中心', '', 100, 'finance.finance/center', 'center', 'finance/center', '', '', 0, 1, 0, 1681613453, 1686729093);
INSERT INTO `ai_system_menu` VALUES (205, 96, 'C', '系统风格', '', 70, '', 'style', 'decoration/style/style', '', '', 0, 1, 0, 1681635044, 1682492058);
INSERT INTO `ai_system_menu` VALUES (207, 224, 'M', '意见反馈', '', 10, '', 'feedback', '', '', '', 0, 1, 0, 1682237448, 1688020104);
INSERT INTO `ai_system_menu` VALUES (208, 207, 'C', '反馈列表', '', 0, 'feedback/lists', 'list', 'feedback/list', '', '', 0, 1, 0, 1682237511, 1686729044);
INSERT INTO `ai_system_menu` VALUES (209, 35, 'C', '系统更新', '', 0, 'settings.system.upgrade/lists', 'update', 'setting/system/update/index', '', '', 0, 1, 0, 1683623304, 1683624316);
INSERT INTO `ai_system_menu` VALUES (210, 209, 'A', '下载更新包', '', 0, 'setting.system.upgrade/downloadPkg', '', '', '', '', 0, 1, 0, 1683628408, 1683628408);
INSERT INTO `ai_system_menu` VALUES (211, 209, 'A', '一键更新', '', 0, 'setting.system.upgrade/upgrade', '', '', '', '', 0, 1, 0, 1683628426, 1683628426);
INSERT INTO `ai_system_menu` VALUES (221, 173, 'C', '注册奖励', '', 200, 'setting.user.user/getRegisterReward', 'register', 'marketing/register/index', '', '', 0, 1, 0, 1684810280, 1686725551);
INSERT INTO `ai_system_menu` VALUES (224, 0, 'M', '应用中心', 'el-icon-Aim', 685, '', 'yingyong', '', '', '', 0, 1, 0, 1685329838, 1685329858);
INSERT INTO `ai_system_menu` VALUES (228, 185, 'A', '新增', '', 0, 'question.questionSample/add', '', '', '', '', 0, 1, 0, 1686715860, 1686715860);
INSERT INTO `ai_system_menu` VALUES (229, 185, 'A', '编辑', '', 0, 'question.questionSample/edit', '', '', '', '', 0, 1, 0, 1686715881, 1686715881);
INSERT INTO `ai_system_menu` VALUES (230, 185, 'A', '删除', '', 0, 'question.questionSample/del', '', '', '', '', 0, 1, 0, 1686715904, 1686715912);
INSERT INTO `ai_system_menu` VALUES (231, 185, 'A', '更新状态', '', 0, 'question.questionSample/status', '', '', '', '', 0, 1, 0, 1686715928, 1686715928);
INSERT INTO `ai_system_menu` VALUES (232, 186, 'A', '新增', '', 0, 'question.questionCategory/add', '', '', '', '', 0, 1, 0, 1686723234, 1686723234);
INSERT INTO `ai_system_menu` VALUES (233, 186, 'A', '编辑', '', 0, 'question.questionCategory/edit', '', '', '', '', 0, 1, 0, 1686723428, 1686723428);
INSERT INTO `ai_system_menu` VALUES (234, 186, 'A', '删除', '', 0, 'question.questionCategory/del', '', '', '', '', 0, 1, 0, 1686723442, 1686723442);
INSERT INTO `ai_system_menu` VALUES (235, 186, 'A', '更新状态', '', 0, 'question.questionCategory/status', '', '', '', '', 0, 1, 0, 1686723717, 1686723717);
INSERT INTO `ai_system_menu` VALUES (236, 191, 'A', '新增', '', 0, 'creation.creationCategory/add', '', '', '', '', 0, 1, 0, 1686723931, 1686723931);
INSERT INTO `ai_system_menu` VALUES (237, 191, 'A', '编辑', '', 0, 'creation.creationCategory/edit', '', '', '', '', 0, 1, 0, 1686723946, 1686723946);
INSERT INTO `ai_system_menu` VALUES (238, 191, 'A', '删除', '', 0, 'creation.creationCategory/del', '', '', '', '', 0, 1, 0, 1686723961, 1686723961);
INSERT INTO `ai_system_menu` VALUES (239, 191, 'A', '更新状态', '', 0, 'creation.creationCategory/status', '', '', '', '', 0, 1, 0, 1686724050, 1686724050);
INSERT INTO `ai_system_menu` VALUES (240, 206, 'A', '新增', '', 0, 'creation.creationModel/add', '', '', '', '', 0, 1, 0, 1686724093, 1686724093);
INSERT INTO `ai_system_menu` VALUES (241, 206, 'A', '编辑', '', 0, 'creation.creationModel/edit', '', '', '', '', 0, 1, 0, 1686724107, 1686724107);
INSERT INTO `ai_system_menu` VALUES (242, 206, 'A', '删除', '', 0, 'creation.creationModel/del', '', '', '', '', 0, 1, 0, 1686724119, 1686724119);
INSERT INTO `ai_system_menu` VALUES (243, 206, 'A', '更新状态', '', 0, 'creation.creationModel/status', '', '', '', '', 0, 1, 0, 1686724141, 1686724141);
INSERT INTO `ai_system_menu` VALUES (244, 196, 'A', '新增', '', 0, 'skill.skill/add', '', '', '', '', 0, 1, 0, 1686724254, 1686724254);
INSERT INTO `ai_system_menu` VALUES (245, 196, 'A', '编辑', '', 0, 'skill.skill/edit', '', '', '', '', 0, 1, 0, 1686724268, 1686724268);
INSERT INTO `ai_system_menu` VALUES (246, 196, 'A', '删除', '', 0, 'skill.skill/del', '', '', '', '', 0, 1, 0, 1686724284, 1686724284);
INSERT INTO `ai_system_menu` VALUES (247, 196, 'A', '更新状态', '', 0, 'skill.skill/status', '', '', '', '', 0, 1, 0, 1686724302, 1686724302);
INSERT INTO `ai_system_menu` VALUES (248, 197, 'A', '新增', '', 0, 'skill.skillCategory/add', '', '', '', '', 0, 1, 0, 1686724420, 1686724420);
INSERT INTO `ai_system_menu` VALUES (249, 197, 'A', '编辑', '', 0, 'skill.skillCategory/edit', '', '', '', '', 0, 1, 0, 1686724444, 1686724444);
INSERT INTO `ai_system_menu` VALUES (250, 197, 'A', '删除', '', 0, 'skill.skillCategory/del', '', '', '', '', 0, 1, 0, 1686724460, 1686724460);
INSERT INTO `ai_system_menu` VALUES (251, 197, 'A', '更新状态', '', 0, '', '', '', '', '', 0, 1, 0, 1686724474, 1686724474);
INSERT INTO `ai_system_menu` VALUES (252, 188, 'A', '详情', '', 0, 'recharge.recharge_order/detail', '', '', '', '', 0, 1, 0, 1686724758, 1686724758);
INSERT INTO `ai_system_menu` VALUES (253, 188, 'A', '退款', '', 0, 'recharge.recharge_order/refund', '', '', '', '', 0, 1, 0, 1686724773, 1686724773);
INSERT INTO `ai_system_menu` VALUES (254, 192, 'A', '详情', '', 0, 'member.member_order/detail', '', '', '', '', 0, 1, 0, 1686724860, 1686724860);
INSERT INTO `ai_system_menu` VALUES (255, 192, 'A', '退款', '', 0, 'member.member_order/refund', '', '', '', '', 0, 1, 0, 1686724884, 1686724884);
INSERT INTO `ai_system_menu` VALUES (256, 118, 'A', '加入黑名单', '', 0, 'user.user/blacklist', '', '', '', '', 0, 1, 0, 1686725043, 1686725043);
INSERT INTO `ai_system_menu` VALUES (257, 119, 'A', '调整会员时间', '', 0, 'user.user/adjustMember', '', '', '', '', 0, 1, 0, 1686725178, 1686725178);
INSERT INTO `ai_system_menu` VALUES (258, 221, 'A', '保存', '', 0, 'setting.user.user/setRegisterReward', '', '', '', '', 0, 1, 0, 1686725578, 1686725578);
INSERT INTO `ai_system_menu` VALUES (259, 175, 'A', '保存', '', 0, 'task.task_share/setConfig', '', '', '', '', 0, 1, 0, 1686725666, 1686725666);
INSERT INTO `ai_system_menu` VALUES (260, 178, 'A', '保存', '', 0, 'task.task_invite/setConfig', '', '', '', '', 0, 1, 0, 1686725773, 1686725773);
INSERT INTO `ai_system_menu` VALUES (261, 180, 'A', '保存', '', 0, 'recharge.recharge_package/setConfig', '', '', '', '', 0, 1, 0, 1686725859, 1686725859);
INSERT INTO `ai_system_menu` VALUES (262, 184, 'A', '新增', '', 0, 'member.member_package/add', '', '', '', '', 0, 1, 0, 1686725960, 1686725960);
INSERT INTO `ai_system_menu` VALUES (263, 184, 'A', '编辑', '', 0, 'member.member_package/edit', '', '', '', '', 0, 1, 0, 1686726044, 1686726044);
INSERT INTO `ai_system_menu` VALUES (264, 184, 'A', '删除', '', 0, 'member.member_package/del', '', '', '', '', 0, 1, 0, 1686726057, 1686726057);
INSERT INTO `ai_system_menu` VALUES (265, 184, 'A', '更新状态', '', 0, 'member.member_package/status', '', '', '', '', 0, 1, 0, 1686726087, 1686726087);
INSERT INTO `ai_system_menu` VALUES (266, 184, 'A', '设置默认', '', 0, 'member.member_package/default', '', '', '', '', 0, 1, 0, 1686726107, 1686726107);
INSERT INTO `ai_system_menu` VALUES (267, 184, 'A', '获取配置', '', 0, 'member.member_package/getConfig', '', '', '', '', 0, 1, 0, 1686726261, 1686726261);
INSERT INTO `ai_system_menu` VALUES (268, 184, 'A', '更新配置', '', 0, 'member.member_package/setConfig', '', '', '', '', 0, 1, 0, 1686726281, 1686726281);
INSERT INTO `ai_system_menu` VALUES (269, 198, 'A', '新增', '', 0, 'member.member_package_comment/add', '', '', '', '', 0, 1, 0, 1686726382, 1686726382);
INSERT INTO `ai_system_menu` VALUES (270, 198, 'A', '删除', '', 0, 'member.member_package_comment/del', '', '', '', '', 0, 1, 0, 1686726399, 1686726399);
INSERT INTO `ai_system_menu` VALUES (271, 200, 'A', '开通分销商', '', 0, 'distribution.distributor/add', '', '', '', '', 0, 1, 0, 1686726548, 1686726548);
INSERT INTO `ai_system_menu` VALUES (272, 200, 'A', '修改分销状态', '', 0, 'distribution.distributor/status', '', '', '', '', 0, 1, 0, 1686726985, 1686726985);
INSERT INTO `ai_system_menu` VALUES (273, 214, 'A', '详情', '', 0, 'distribution.distributionApply/detail', '', '', '', '', 0, 1, 0, 1686727703, 1686727703);
INSERT INTO `ai_system_menu` VALUES (274, 214, 'A', '审核', '', 0, 'distribution.distributionApply/audit', '', '', '', '', 0, 1, 0, 1686727733, 1686727733);
INSERT INTO `ai_system_menu` VALUES (275, 217, 'A', '提现审核', '', 0, 'distribution.withdraw/verify', '', '', '', '', 0, 1, 0, 1686728422, 1686728422);
INSERT INTO `ai_system_menu` VALUES (276, 217, 'A', '转账', '', 0, 'distribution.withdraw/transfer', '', '', '', '', 0, 1, 0, 1686728441, 1686728441);
INSERT INTO `ai_system_menu` VALUES (277, 217, 'A', '提现详情', '', 0, 'distribution.withdraw/detail', '', '', '', '', 0, 1, 0, 1686728788, 1686728788);
INSERT INTO `ai_system_menu` VALUES (278, 218, 'A', '保存', '', 0, 'distribution.config/setConfig', '', '', '', '', 0, 1, 0, 1686728929, 1686728929);
INSERT INTO `ai_system_menu` VALUES (279, 219, 'A', '保存', '', 0, 'distribution.withdraw/setConfig', '', '', '', '', 0, 1, 0, 1686728980, 1686728980);
INSERT INTO `ai_system_menu` VALUES (283, 202, 'A', '保存', '', 0, 'setting.shareSetting/setConfig', '', '', '', '', 0, 1, 0, 1686729675, 1686729675);
INSERT INTO `ai_system_menu` VALUES (288, 183, 'C', '会员权益', '', 0, 'member.member_benefits/lists', 'legal_right', 'marketing/vip_combo/legal_right/index', '', '', 0, 1, 0, 1687142635, 1687859683);
INSERT INTO `ai_system_menu` VALUES (292, 0, 'M', 'AI绘画', 'local-icon-zhuangxiu', 935, '', 'ai_drawing', '', '', '', 0, 1, 0, 1687245455, 1688093193);
INSERT INTO `ai_system_menu` VALUES (298, 288, 'A', '新增', '', 0, 'member.member_benefits/add', '', '', '', '', 0, 1, 0, 1687859703, 1687859703);
INSERT INTO `ai_system_menu` VALUES (299, 288, 'A', '编辑', '', 0, 'member.member_benefits/edit', '', '', '', '', 0, 1, 0, 1687859721, 1687859721);
INSERT INTO `ai_system_menu` VALUES (300, 288, 'A', '删除', '', 0, 'member.member_benefits/del', '', '', '', '', 0, 1, 0, 1687859753, 1687859914);
INSERT INTO `ai_system_menu` VALUES (301, 288, 'A', '修改状态', '', 0, 'member.member_benefits/status', '', '', '', '', 0, 1, 0, 1687859778, 1687859778);
INSERT INTO `ai_system_menu` VALUES (302, 183, 'C', '会员套餐新增编辑', '', 0, 'member.member_package/add:edit', 'combo/edit', 'marketing/vip_combo/edit_vip', '/marketing/vipcombo/combo', '', 0, 0, 0, 1687860071, 1687860071);
INSERT INTO `ai_system_menu` VALUES (303, 173, 'C', '充值套餐添加/编辑', '', 65, 'recharge.recharge_package/add:edit', 'recharge/edit', 'marketing/recharge/edit', '/marketing/recharge', '', 0, 0, 0, 1687941211, 1687941556);
INSERT INTO `ai_system_menu` VALUES (304, 180, 'A', '新增', '', 0, 'recharge.recharge_package/add', '', '', '', '', 0, 1, 0, 1687941309, 1687941309);
INSERT INTO `ai_system_menu` VALUES (305, 180, 'A', '编辑', '', 0, 'recharge.recharge_package/edit', '', '', '', '', 0, 1, 0, 1687941323, 1687941323);
INSERT INTO `ai_system_menu` VALUES (306, 180, 'A', '删除', '', 0, 'recharge.recharge_package/del', '', '', '', '', 0, 1, 0, 1687941342, 1687941342);
INSERT INTO `ai_system_menu` VALUES (307, 180, 'A', '修改状态', '', 0, 'recharge.recharge_package/status', '', '', '', '', 0, 1, 0, 1687941364, 1687941364);
INSERT INTO `ai_system_menu` VALUES (308, 180, 'A', '修改推荐', '', 0, 'echarge.recharge_package/recommend', '', '', '', '', 0, 1, 0, 1687941396, 1687941396);
INSERT INTO `ai_system_menu` VALUES (317, 119, 'A', '调整绘画余额', '', 0, 'user.user/adjustUserDraw', '', '', '', '', 0, 1, 0, 1688010816, 1688010816);
INSERT INTO `ai_system_menu` VALUES (326, 292, 'C', 'LunaAI写真', '', 1, 'luna_drawing_task/lists', 'luna_drawing_task', 'luna_drawing_task/index', '', '', 0, 1, 0, 1699325364, 1721777099);
INSERT INTO `ai_system_menu` VALUES (327, 326, 'A', '添加', '', 1, 'luna_drawing_task/add', '', '', '', '', 0, 1, 0, 1699325364, 1699325364);
INSERT INTO `ai_system_menu` VALUES (328, 326, 'A', '编辑', '', 1, 'luna_drawing_task/edit', '', '', '', '', 0, 1, 0, 1699325364, 1699325364);
INSERT INTO `ai_system_menu` VALUES (329, 326, 'A', '删除', '', 1, 'luna_drawing_task/delete', '', '', '', '', 0, 1, 0, 1699325364, 1699325364);
INSERT INTO `ai_system_menu` VALUES (330, 28, 'C', '图片审核', '', 0, 'setting.imageCheckSetting/getConfig', 'image_check', 'setting/image_check/index', '', '', 0, 1, 0, 1721633909, 1721633909);
INSERT INTO `ai_system_menu` VALUES (331, 330, 'A', '保存', '', 0, 'setting.imageCheckSetting/setConfig', '', '', '', '', 0, 1, 0, 1721633956, 1721633956);
INSERT INTO `ai_system_menu` VALUES (332, 28, 'C', 'Luna算法', '', 0, 'setting.lunaServiceSetting/getConfig', 'luna_service', 'setting/luna_service/index', '', '', 0, 1, 0, 1721638086, 1721638086);
INSERT INTO `ai_system_menu` VALUES (333, 332, 'A', '保存', '', 0, 'setting.lunaServiceSetting/setConfig', '', '', '', '', 0, 1, 0, 1721638119, 1721638119);

-- ----------------------------
-- Table structure for ai_system_role
-- ----------------------------
DROP TABLE IF EXISTS `ai_system_role`;
CREATE TABLE `ai_system_role`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `desc` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `sort` int NULL DEFAULT 0 COMMENT '排序',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_system_role
-- ----------------------------

-- ----------------------------
-- Table structure for ai_system_role_menu
-- ----------------------------
DROP TABLE IF EXISTS `ai_system_role_menu`;
CREATE TABLE `ai_system_role_menu`  (
  `role_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色ID',
  `menu_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '菜单ID',
  PRIMARY KEY (`role_id`, `menu_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色菜单关系表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_system_role_menu
-- ----------------------------

-- ----------------------------
-- Table structure for ai_task_invite
-- ----------------------------
DROP TABLE IF EXISTS `ai_task_invite`;
CREATE TABLE `ai_task_invite`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT '邀请人id',
  `new_user_id` int NULL DEFAULT NULL COMMENT '新用户ID',
  `task_share_id` int NULL DEFAULT NULL COMMENT '分享链接ID',
  `rewards` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '获得奖励',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '邀请记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_task_invite
-- ----------------------------

-- ----------------------------
-- Table structure for ai_task_share
-- ----------------------------
DROP TABLE IF EXISTS `ai_task_share`;
CREATE TABLE `ai_task_share`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT '用户ID',
  `channel` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '分享渠道: [1-微信小程序 2-微信公众号 3-手机H5 4-电脑PC 5-苹果APP 6-安卓APP]',
  `click_num` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '点击量',
  `invite_num` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '成功邀请人数',
  `rewards` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '获得奖励',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '分享记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_task_share
-- ----------------------------

-- ----------------------------
-- Table structure for ai_user
-- ----------------------------
DROP TABLE IF EXISTS `ai_user`;
CREATE TABLE `ai_user`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `sn` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '编号',
  `avatar` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '头像',
  `real_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '真实姓名',
  `nickname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '用户昵称',
  `account` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '用户账号',
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '用户密码',
  `mobile` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '用户电话',
  `sex` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户性别: [1=男, 2=女]',
  `channel` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '注册渠道: [1-微信小程序 2-微信公众号 3-手机H5 4-电脑PC 5-苹果APP 6-安卓APP]',
  `is_disable` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否禁用: [0=否, 1=是]',
  `login_ip` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `login_time` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后登录时间',
  `is_new_user` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否是新注册用户: [1-是, 0-否]',
  `balance` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '余额（条数）',
  `balance_draw` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '绘画余额（条数）',
  `total_quiz` int NOT NULL DEFAULT 0 COMMENT '累计提问次数',
  `total_draw` int NOT NULL DEFAULT 0 COMMENT '累计绘画次数',
  `total_amount` decimal(10, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '累计消费金额',
  `member_end_time` int NULL DEFAULT NULL COMMENT '会员到期时间',
  `member_perpetual` tinyint(1) NULL DEFAULT 0 COMMENT '会员是否永久 ：1-是；0-否',
  `is_distribution` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否分销商: [1-是, 0-否]',
  `distribution_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '分销状态：1-正常；0-冻结',
  `distribution_time` int NULL DEFAULT NULL COMMENT '成为分销商的时间',
  `inviter_id` int NOT NULL DEFAULT 0 COMMENT '邀请人id',
  `first_leader` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '第一个上级',
  `second_leader` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '第二个上级',
  `user_money` decimal(10, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '可提现佣金',
  `total_user_money` decimal(10, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '累计总佣金',
  `is_blacklist` tinyint(1) NULL DEFAULT 0 COMMENT '是否在黑名单：1-是；0-否；',
  `cancelled_remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '注销原因',
  `create_time` int UNSIGNED NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int UNSIGNED NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int UNSIGNED NULL DEFAULT NULL COMMENT '删除时间',
  `provider` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '第三方登录平台。如google,apple,email',
  `email` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '邮箱地址',
  `login_ipinfo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '登录IP信息',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `sn`(`sn` ASC) USING BTREE COMMENT '编号唯一'
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '用户表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_user
-- ----------------------------

-- ----------------------------
-- Table structure for ai_user_account_log
-- ----------------------------
DROP TABLE IF EXISTS `ai_user_account_log`;
CREATE TABLE `ai_user_account_log`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `sn` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '流水号',
  `user_id` int NOT NULL COMMENT '用户id',
  `change_object` tinyint(1) NOT NULL DEFAULT 0 COMMENT '变动对象',
  `change_type` smallint NOT NULL COMMENT '变动类型',
  `action` tinyint(1) NOT NULL DEFAULT 0 COMMENT '动作 1-增加 2-减少',
  `change_amount` decimal(10, 2) NOT NULL COMMENT '变动数量',
  `left_amount` decimal(10, 2) NOT NULL DEFAULT 100.00 COMMENT '变动后数量',
  `source_sn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '关联单号',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '' COMMENT '备注',
  `extra` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '预留扩展字段',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_uid`(`user_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '账户流水记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_user_account_log
-- ----------------------------

-- ----------------------------
-- Table structure for ai_user_auth
-- ----------------------------
DROP TABLE IF EXISTS `ai_user_auth`;
CREATE TABLE `ai_user_auth`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT '用户id',
  `openid` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '微信openid',
  `unionid` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '' COMMENT '微信unionid',
  `terminal` tinyint(1) NOT NULL DEFAULT 1 COMMENT '客户端类型：1-微信小程序；2-微信公众号；3-手机H5；4-电脑PC；5-苹果APP；6-安卓APP',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `openid`(`openid` ASC) USING BTREE,
  INDEX `idx_unionid`(`unionid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '用户授权表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_user_auth
-- ----------------------------

-- ----------------------------
-- Table structure for ai_user_auth_dy
-- ----------------------------
DROP TABLE IF EXISTS `ai_user_auth_dy`;
CREATE TABLE `ai_user_auth_dy`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT '用户id',
  `openid` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '抖音openid',
  `unionid` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '' COMMENT '抖音unionid',
  `terminal` tinyint(1) NOT NULL DEFAULT 7 COMMENT '客户端类型：7-抖音小程序',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `openid`(`openid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '抖音用户授权表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_user_auth_dy
-- ----------------------------

-- ----------------------------
-- Table structure for ai_user_session
-- ----------------------------
DROP TABLE IF EXISTS `ai_user_session`;
CREATE TABLE `ai_user_session`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT '用户id',
  `terminal` tinyint(1) NOT NULL DEFAULT 1 COMMENT '客户端类型：1-微信小程序；2-微信公众号；3-手机H5；4-电脑PC；5-苹果APP；6-安卓APP',
  `token` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '令牌',
  `update_time` int NULL DEFAULT NULL COMMENT '更新时间',
  `expire_time` int NOT NULL COMMENT '到期时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `admin_id_client`(`user_id` ASC, `terminal` ASC) USING BTREE COMMENT '一个用户在一个终端只有一个token',
  UNIQUE INDEX `token`(`token` ASC) USING BTREE COMMENT 'token是唯一的'
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '用户会话表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ai_user_session
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `ai_luna_template_group`
(
    `id`            int unsigned                                                  NOT NULL AUTO_INCREMENT COMMENT '主键',
    `name`          varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分组名称',
    `category`        varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '分类',
    `up_group_id`   int unsigned                                                  NOT NULL DEFAULT '0' COMMENT '上游分组ID',
    `up_group_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '上游分组名称',
    `sort`          smallint unsigned                                             NOT NULL DEFAULT '0' COMMENT '排序',
    `create_time`   int                                                           NULL     DEFAULT NULL COMMENT '创建时间',
    `update_time`   int                                                           NULL     DEFAULT NULL COMMENT '更新时间',
    `delete_time`   int                                                           NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT ='Luna模板分组';

CREATE TABLE `ai_luna_template`
(
    `id`             int unsigned                                                  NOT NULL AUTO_INCREMENT COMMENT '主键',
    `group_id`       int unsigned                                                  NOT NULL DEFAULT '0' COMMENT '分组ID',
    `up_template_id` bigint unsigned                                               NOT NULL DEFAULT '0' COMMENT '上游模板ID',
    `image_url`      text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NOT NULL COMMENT '图片地址',
    `name`           varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '模板名称',
    `sort`           smallint unsigned                                             NOT NULL DEFAULT '0' COMMENT '排序',
    `create_time`    int                                                           NULL     DEFAULT NULL COMMENT '创建时间',
    `update_time`    int                                                           NULL     DEFAULT NULL COMMENT '更新时间',
    `delete_time`    int                                                           NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT ='Luna模板';

INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (1, '万事兴龙', 'avatar', 122, '万事兴龙', 0, 1722191342, 1722191342, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (2, '多巴胺', 'avatar', 27, '多巴胺', 1910, 1722191342, 1722191342, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (3, '盐系简约', 'avatar', 25, '盐系简约', 2000, 1722191342, 1722191342, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (4, '古风', 'avatar', 23, '古风', 2100, 1722191342, 1722191342, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (5, '泰酷辣', 'avatar', 24, '泰酷辣', 1800, 1722191342, 1722191342, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (6, '莫吉托', 'avatar', 28, '莫吉托', 1900, 1722191342, 1722191342, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (7, '跃动青春', 'avatar', 44, '跃动青春', 0, 1722191342, 1722191342, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (8, '旅游', 'avatar', 46, '旅游', 0, 1722191342, 1722191342, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (9, '新年新气象', '1v1', 118, '新年新气象', 0, 1722191342, 1722191342, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (10, '繁花', '1v1', 121, '繁花', 900, 1722191342, 1722191342, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (11, '红包封面', '1v1', 119, '红包封面', 0, 1722191342, 1722191342, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (12, '携手同行', '1v1', 120, '携手同行', 0, 1722191342, 1722191342, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (13, '年画娃娃', '1v1', 114, '年画娃娃', 0, 1722191342, 1722191342, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (14, '剩单快乐', '1v1', 76, '剩单快乐', 0, 1722191342, 1722191342, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (15, '玩梗不闻会君', '1v1', 78, '玩梗不闻会君', 800, 1722191342, 1722191342, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (16, '暖冬胶片', '1v1', 77, '暖冬胶片', 0, 1722191342, 1722191342, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (17, 'Ins风', '1v1', 80, '高质量男女', 950, 1722191342, 1722191342, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (18, '高管证件照-女', '1v1', 79, '高管证件照', 880, 1722191342, 1722191342, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (19, '科幻', 'film', 19, '科幻', 0, 1722196002, 1722196002, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (20, '剧情', 'film', 20, '剧情', 0, 1722196002, 1722196002, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (21, '奇幻', 'film', 21, '奇幻', 0, 1722196003, 1722196003, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (22, '历史剧', 'film', 22, '历史剧', 0, 1722196003, 1722196003, null);
INSERT INTO ai_luna_template_group (id, name, category, up_group_id, up_group_name, sort, create_time, update_time, delete_time) VALUES (23, '高管证件照-男', '1v1', 0, '', 1000, 1722191342, 1722191342, null);

INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (1, 1, 3017844, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/2f21cdd00332ed177e4f03c075f471b3.webp', '万事兴龙_db6cc4ef414c1496c05d7b9ff92b6857.webp', 0, 1722521506, 1722526445, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (2, 1, 3017832, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/4894325b636771925628d0c55e02b29a.webp', '万事兴龙_b6b2a93103251e5ebfd575e2a83b8e18.webp', 0, 1722521506, 1722526447, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (3, 1, 3017831, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/062e0d6cd7113707cc94ab613380727b.webp', '万事兴龙_c5582545c7369d3b44964e2d0e18b841.webp', 0, 1722521506, 1722526450, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (4, 1, 3017833, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/fd5fea2793fb1ea0b486b2feea523076.webp', '万事兴龙_224f41f2b2a22b4d6d9bed7d8192b5eb.webp', 0, 1722521506, 1722526452, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (5, 1, 3018028, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/209eab3025d44b556eda83d887ec693c.webp', '万事兴龙_7d3ce32742ab92eb78b49fe759cee74e.webp', 0, 1722521506, 1722526454, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (6, 2, 2473569, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/378c9b750b6f00fbdfe0add6fb9ea5dc.webp', '多巴胺_892cd7d9d81045649f5d1951ad2a133a.webp', 0, 1722521506, 1722526455, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (7, 2, 2418640, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/0fbacf3271fa112279c5124d137f4f23.webp', '多巴胺_664e53b72e804ec786994730f038c50a.webp', 0, 1722521506, 1722526458, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (8, 2, 2418651, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/2487eef3fb0595d6a8a985cae2f9e4ab.webp', '多巴胺_7e78be5eba934a84bc3b254ac7083b44.webp', 0, 1722521506, 1722526459, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (9, 2, 2473578, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/959f9e4dab31e066a222945497a11a3d.webp', '多巴胺_7d220d4c127e4a228f9c5381ef0460c7.webp', 0, 1722521506, 1722526460, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (10, 2, 2473570, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/bc42fff4e8fdee9f0a08dc7db593bc04.webp', '多巴胺_c1887b3230604dc2b5687e3e860a96a7.webp', 0, 1722521506, 1722526462, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (11, 2, 2473576, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/7c2c62a7bd7df510c92d9a1593c5193b.webp', '多巴胺_80634460c6ed457b834067395da5a452.webp', 0, 1722521506, 1722526464, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (12, 2, 2473567, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/21973fd5a90fc73954471d68bd834449.webp', '多巴胺_046403acc33540a8ba8ed2f5e2d5855e.webp', 0, 1722521506, 1722526479, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (13, 2, 2473571, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/bf37333be2700f3ab822a8d0792782a0.webp', '多巴胺_11d97b906253436892317231650c7412.webp', 0, 1722521506, 1722526481, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (14, 2, 2418579, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/cafef90015a73ce0fd613e32dc645866.webp', '多巴胺_5f98ced90bd542a99fa30a9fb9860f94.webp', 0, 1722521506, 1722526482, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (15, 2, 2418589, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/12c33a0891785cf9f124c73daf5ec35c.webp', '多巴胺_60ebabc7d1104b80aaf22636f9d813a7.webp', 0, 1722521506, 1722526484, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (16, 3, 2398677, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/c5d61b275cb5b544067e7e0671279685.webp', '盐系简约_4b6b6cb2b4d04ef8a5af661e93dbc6d1.webp', 0, 1722521506, 1722526486, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (17, 3, 2398561, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/e1debd980f58da41797bec1b9a107145.webp', '盐系简约_3eee983000334bde81007bf25b413240.webp', 0, 1722521506, 1722526488, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (18, 3, 2398671, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/ab60f9e344bd507dc4dbb33f4cf85a73.webp', '盐系简约_d49c3a1d878449eda1df220cdcd5bf9e.webp', 0, 1722521506, 1722526489, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (19, 3, 72965, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/2b53a90b86dfd7ca76c49eb8d2814951.webp', '盐系简约_6add7ee6d400453ea828123b99b06aca.webp', 0, 1722521506, 1722526491, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (20, 3, 2398549, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/85b33c4122be9f828f178fc6be9ac9c5.webp', '盐系简约_1e03dd732bde4ca8a33bdbe46b38a6f6.webp', 0, 1722521506, 1722526492, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (21, 3, 2398578, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/9199d19991cddd3f90a5bec018ea057d.webp', '盐系简约_109c8e2cdd0e46eca6256ba2b4a13fe4.webp', 0, 1722521506, 1722526495, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (22, 3, 2398613, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/26d9ef1b7812f67ab4b8c53845908036.webp', '盐系简约_e34ff2b440e9484ea8763f5deff97ad3.webp', 0, 1722521506, 1722526496, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (23, 3, 2398764, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/848e6bd5635744d34d8c4cff98abe648.webp', '盐系简约_0310f991dca54974adf96dbe3bf01145.webp', 0, 1722521506, 1722526497, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (24, 4, 2417479, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/c8deca357f3538c3035ba3c7a38ebc27.webp', '古风_7076a9e7eee446c8975ec0268a942a92.webp', 0, 1722521507, 1722526499, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (25, 4, 2398541, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/a79dbf72a42bb4514fcc31c652598335.webp', '古风_66119ecc21c046cd87d14f6d885d9dc7.webp', 0, 1722521507, 1722526500, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (26, 4, 2398542, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/4d62a074215d111cbd0647f13d81a2a8.webp', '古风_10d30e80afa647c2a6feb9bf257491e3.webp', 0, 1722521507, 1722526501, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (27, 4, 2398544, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/628de4a3ec21935fb69ee8c600d9698d.webp', '古风_f60cdf2cb7aa4d569aaac6ce4d20999b.webp', 0, 1722521507, 1722526503, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (28, 4, 2398590, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/cf46b07e2ad3761ca00eb46d9b4aec84.webp', '古风_76b04dfaba3f473f9b3e4991a6642e19.webp', 0, 1722521507, 1722526505, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (29, 4, 2417477, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/2e183531314530969bdea0859bada756.webp', '古风_71e7e637744e42b18fecd4f13a125f29.webp', 0, 1722521507, 1722526507, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (30, 4, 2417478, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/367f822f9724ec414de470299aee8d3b.webp', '古风_26a8f88306c248e99b451d1155299dfb.webp', 0, 1722521507, 1722526508, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (31, 4, 2417482, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/7385fd339020fa0edea75dc7424eb186.webp', '古风_4dc9f310c9824d149e290e1065eeb66a.webp', 0, 1722521507, 1722526510, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (32, 4, 2417817, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/e20d2d83c00e75496022d9ca462adcdb.webp', '古风_e635dfaed38c44efbbc8335c3d256e34.webp', 0, 1722521507, 1722526513, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (33, 5, 2398566, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/2bc2762260dbf7dc3f0b58290a8830ed.webp', '泰酷辣_c5eec6fa968f4d5fb992fbd54e967ba1.webp', 0, 1722521507, 1722526514, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (34, 5, 26602, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/58a852c29f76047d602df3d9f26118f0.webp', '泰酷辣_06b2ddc14e97441eb9816e44149bb688.webp', 0, 1722521507, 1722526515, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (35, 5, 2398555, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/48c0f402e21fbc1f8d5b98efce562c69.webp', '泰酷辣_1c15284fa11443fbb171392afcc12260.webp', 0, 1722521507, 1722526518, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (36, 5, 2398577, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/22f24b04054db0d117747463a0b1d876.webp', '泰酷辣_cedab89ef4da4703b5ba62d5f5e3e90e.webp', 0, 1722521507, 1722526522, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (37, 5, 2398656, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/9f10ad5af53560763a108049c2058d22.webp', '泰酷辣_21f853e36d7d452eb67ed7a5fb4da137.webp', 0, 1722521507, 1722526523, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (38, 5, 2398699, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/9428dcbf0f007d718fcefe295fbe6a52.webp', '泰酷辣_1e7f0e5909e84a17b5eae3048370dc7c.webp', 0, 1722521507, 1722526525, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (39, 5, 2415907, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/4ed5325317038f796e77322ab3d698b0.webp', '泰酷辣_545f0730ff104302afb42cc3daa7667b.webp', 0, 1722521507, 1722526527, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (40, 6, 2398539, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/87975df9aa1e11c2e896cb356eeeade5.webp', '莫吉托_bf57f4fccab1480fa5ec1febac855ea0.webp', 0, 1722521507, 1722526528, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (41, 6, 11030, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/244907316bb09e961c74b52bbd0740ff.webp', '莫吉托_d91c3063d735497792d3476fea0e6cb4.webp', 0, 1722521507, 1722526529, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (42, 6, 19652, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/c324204be10f627100d93e5c5f869ff3.webp', '莫吉托_b6a85ae86a43428ca5729e1736534f05.webp', 0, 1722521507, 1722526531, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (43, 6, 24188, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/0433e07c90ab45648cc308491b911217.webp', '莫吉托_3b63dd2016ba44e09c072f7c2f5837a1.webp', 0, 1722521507, 1722526535, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (44, 6, 2398546, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/251aaf2980514719a3c41c909c27aca1.webp', '莫吉托_a224600ab6bd4e97ad48ff2e94c6fcf8.webp', 0, 1722521507, 1722526537, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (45, 6, 2492605, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/497128851c8560cdda44af9ad1106ce3.webp', '莫吉托_a5afe34fd29d449c94184340e79e0dff.webp', 0, 1722521507, 1722526539, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (46, 6, 2398570, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/e7689f4baf87e4d70fad32eb4e3241c0.webp', '莫吉托_1422251a44294eae96313b01ce1595e4.webp', 0, 1722521507, 1722526540, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (47, 6, 2398591, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/a3f42cbe3e7069a11fc5244df2afc539.webp', '莫吉托_d650f288c88346a59f895e4a3a052afa.webp', 0, 1722521507, 1722526541, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (48, 7, 2599459, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/47d441da56f51285c20637fd2e2110aa.webp', '跃动青春_53450c4ce6dd36aa96c6dad50cbdecfb.webp', 0, 1722521507, 1722526543, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (49, 8, 2599456, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/7bbaccf0326b325853baeeb4b17737de.webp', '旅游_9e8eabd8df79007e4633627fbd1ac2fc.webp', 0, 1722521507, 1722526544, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (50, 9, 3017793, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/0f6fd5d23691838619ee0eb49a9dafa6.webp', '新年新气象_79298423f69c482dbad5e9b0564a4a6b.webp', 0, 1722521507, 1722526546, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (51, 9, 3017802, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/e0446ca75f2e0960b31dcce6ffecfcac.webp', '新年新气象_fce194ef309b4daa85ee6695d165c04d.webp', 0, 1722521507, 1722526547, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (52, 9, 3017806, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/655703dea68003add8b3a17290165367.webp', '新年新气象_e40d29eca93a429891e3122b04c65125.webp', 0, 1722521507, 1722526549, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (53, 9, 3017798, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/81b071a176e583aca3538345f6b0b62e.webp', '新年新气象_879acaa36f4641f79e99b9d102e5400c.webp', 0, 1722521507, 1722526550, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (54, 9, 3017796, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/62f1dffa78d99f03ebf6223ea816eab3.webp', '新年新气象_d1fbd69d2a084fbe892734d648b52755.webp', 0, 1722521507, 1722526552, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (55, 9, 3017800, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/cfb5ce89074a8e2d5373e6ce01ac5d3d.webp', '新年新气象_b75c3126a50e468c928b3772ea93a0c2.webp', 0, 1722521507, 1722526555, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (56, 9, 3017811, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/4e1df6b809b45bc52d187730faf0af7a.webp', '新年新气象_8c8fe1cf87d345729729b0ae8a3c734e.webp', 0, 1722521507, 1722526558, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (57, 9, 3017804, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/7008f8facf3a387a88d0f10c48d3e974.webp', '新年新气象_84d0e5540b4a43be97db24d7f1d800e3.webp', 0, 1722521507, 1722526559, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (58, 9, 3017810, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/c6b8f2a32bfa94f3df6b2c41043a24fc.webp', '新年新气象_3b34f128feca4f9f9b21005f76525cbd.webp', 0, 1722521507, 1722526560, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (59, 9, 3017794, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/c478be04163973943834a06ab5678412.webp', '新年新气象_cf2a4ff4e62f44ad926a7715f41a1b18.webp', 0, 1722521507, 1722526563, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (60, 9, 3017808, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/e500f23491f5dfcaba310ac45249d388.webp', '新年新气象_124500dff2254b5882096334c3a6b1f4.webp', 0, 1722521507, 1722526564, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (61, 10, 3017819, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/545b4fa7af14bbf6fe4246e4f53caafc.webp', '繁花_59fde99196db428c9727123d028df780.webp', 0, 1722521507, 1722526566, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (62, 10, 3017813, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/62a9861f792eb0a38c74f65ca4f94d81.webp', '繁花_80209526c3514446b8df55a8e48a961d.webp', 0, 1722521507, 1722526567, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (63, 10, 3017825, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/d3d5dc777fcb9f653c26c797c733899b.webp', '繁花_76312191ec2c41508a3099f53f1a0c8a.webp', 0, 1722521507, 1722526569, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (64, 10, 3017827, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/6453d9237649e9ec388f1fec8e82e6de.webp', '繁花_ecf1b4d1198549468846af83a34304c4.webp', 0, 1722521507, 1722526572, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (65, 10, 3017821, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/9cd661112bb741f7a6fdf5ffc8de6a49.webp', '繁花_86511ca05c4c48c69d6a06ecd0eff4a7.webp', 0, 1722521507, 1722526573, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (66, 10, 3017823, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/e6acb08fe92354c863b53bde9f0b4202.webp', '繁花_f129ae46903e42c6ac83a27ac014aa9a.webp', 0, 1722521507, 1722526574, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (67, 10, 3017815, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/42f720b52ed98f5582fb47779494b9b4.webp', '繁花_e73ad4fd17074049b66e919e975b7dd4.webp', 0, 1722521507, 1722526576, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (68, 10, 3017817, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/1041c8adfe0fac052f16af42fd151b37.webp', '繁花_aeefd5ad994c4784a483dba8e7ddc4ea.webp', 0, 1722521507, 1722526581, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (69, 11, 3017792, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/b881b9fb658a6a158b155ae2083675fc.webp', '红包封面_2db57d4679c845de8993402f66d53c3e.webp', 0, 1722521507, 1722526583, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (70, 11, 3017803, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/2482ccb1bf74968bdfc4edf24e5bcb2d.webp', '红包封面_fa88c7ac8d404bd78e27f00114780674.webp', 0, 1722521508, 1722526584, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (71, 11, 3017799, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/4a9e29a7dd73c578bc278583cede56b9.webp', '红包封面_472e056374b348b99071e255aa0f057f.webp', 0, 1722521508, 1722526585, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (72, 11, 3017795, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/98d905d772ffa54bf19f035453f59263.webp', '红包封面_e21f704c1cf947869ab2841e453ecd45.webp', 0, 1722521508, 1722526588, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (73, 11, 3017809, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/9dd8bf619cba07659bd06f6e8908cfc6.webp', '红包封面_a7315940829449a68698a8baaf86703e.webp', 0, 1722521508, 1722526589, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (74, 11, 3017805, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/579635b0f63122b379e581daa59bf40c.webp', '红包封面_bbfdd2f7025e4be7a8ed1be1283f2180.webp', 0, 1722521508, 1722526591, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (75, 11, 3017797, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/9b8c3bb42321fe32c888e3663e48317d.webp', '红包封面_b72f1f369f1647e48c0dc8e3e884e7b2.webp', 0, 1722521508, 1722526594, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (76, 11, 3017801, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/1397a403bcdb44841b27c76abd9f1d2d.webp', '红包封面_610d76c5b099483986c11dda348f58d4.webp', 0, 1722521508, 1722526595, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (77, 11, 3017807, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/0b4a78c9101c3b5d49bfc89ca62b659a.webp', '红包封面_069dfea0153641c9ab6ac36fd9a3ac83.webp', 0, 1722521508, 1722526597, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (78, 12, 3017818, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/13cb38ddc70d9fbda961db0cfe8598a2.webp', '携手同行_d3acb31b546f411fa5d3383529e1c5d3.webp', 0, 1722521508, 1722526598, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (79, 12, 3017830, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/aae424e2931a3aeab379fa54402a5d96.webp', '携手同行_0f67255751b44dbb8f6722b958f3991e.webp', 0, 1722521508, 1722526600, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (80, 12, 3017814, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/d65bb6a2e64777297362c5354fa3130d.webp', '携手同行_53693572d5b141d9ab0a5059d50ed4d9.webp', 0, 1722521508, 1722526602, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (81, 12, 3017816, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/8b9e37fd210c9dc9c079cf9140449192.webp', '携手同行_05e7a57153f643f6bbb3f9e4d4ac5d0a.webp', 0, 1722521508, 1722526604, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (82, 12, 3017826, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/95e7bf0bb7d6c6a8d2f2b97890a755e9.webp', '携手同行_86c828bdd0ca43eb9a286ac07c4662f7.webp', 0, 1722521508, 1722526605, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (83, 12, 3017812, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/532e1551fcb4d4d85e79a24ccb0b34ff.webp', '携手同行_d635ec7e3af94057b154a84236d04958.webp', 0, 1722521508, 1722526606, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (84, 12, 3017820, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/5383d3a3dddb7038032a62e34ef23052.webp', '携手同行_db31082f94434d5cb53fe46f155f7ea8.webp', 0, 1722521508, 1722526608, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (85, 12, 3017822, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/63ebca655de100c7839a71a7c19bdfa1.webp', '携手同行_33ded3b7a7894f4f84d11ecd423d4d8a.webp', 0, 1722521508, 1722526610, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (86, 12, 3017829, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/9fa6c85787e0113592301d9090346645.webp', '携手同行_5904dd1cc9144b67a1220133ca63b605.webp', 0, 1722521508, 1722526612, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (87, 12, 3017824, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/19edc1a359f675fcf8f82b7c1e3e22a5.webp', '携手同行_2f5d9aff080f4bb8a519bbc7010ad376.webp', 0, 1722521508, 1722526613, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (88, 12, 3017828, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/4321c700491446f4899cace7b245a9d4.webp', '携手同行_aca443acaa4e41e4b6576040347e9037.webp', 0, 1722521508, 1722526616, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (89, 13, 3014719, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/4ba741610f87d4c4efa717f267962e25.webp', '年画娃娃_a248e1ba61b54aec94436341136cb2c5.webp', 0, 1722521508, 1722526617, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (90, 13, 3014714, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/d82c35c0c5df0702476612efd44cd12e.webp', '年画娃娃_27f49078d5404379b30c1612df13684a.webp', 0, 1722521508, 1722526620, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (91, 13, 3014715, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/227766072ec2bfd549d58b8aecc454a9.webp', '年画娃娃_c988dfea44344391a5f4e7598c6b191a.webp', 0, 1722521508, 1722526621, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (92, 13, 3014708, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/ec16ed92eb4be029392db3455d2db086.webp', '年画娃娃_b3bf931b8732441783a8fc39c5c46cbe.webp', 0, 1722521508, 1722526623, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (93, 13, 3014725, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/89fc32bbe3525569b088757fdd704265.webp', '年画娃娃_7c79f8615f9e47828a3c7769578a78fb.webp', 0, 1722521508, 1722526624, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (94, 13, 3014709, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/ede612d048e43dee39728051a499d61d.webp', '年画娃娃_936fa148774a4502892fc4a3486a9588.webp', 0, 1722521508, 1722526626, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (95, 13, 3014723, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/08ab2419ab9601244f8c59cb78dd672b.webp', '年画娃娃_4e0737efa4874149ab76f48affc4f238.webp', 0, 1722521508, 1722526627, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (96, 13, 3014721, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/97086e621b2afe7f8d4f76b565fcedee.webp', '年画娃娃_0fc2b7e66c6345ea941709be2f4ea302.webp', 0, 1722521508, 1722526628, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (97, 13, 3014703, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/7df7deacd9cf3fb627ddf500d9590dbb.webp', '年画娃娃_8f194f7fe073463abdb91419edf459ec.webp', 0, 1722521508, 1722526630, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (98, 13, 3014707, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/fc89dea5529457c39831b07899e73d69.webp', '年画娃娃_0303e89333f24facb1c6154fd06b552d.webp', 0, 1722521508, 1722526631, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (99, 13, 3014720, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/ffff8eb58b32cf8d6ad69121c736ec86.webp', '年画娃娃_07e12baf025c4bb3b620ab341aa7c7b0.webp', 0, 1722521508, 1722526633, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (100, 13, 3014704, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/5955773f6cb06cb41eb04110c6e5110e.webp', '年画娃娃_b20768fdb43b4e9e97d1d58f73689895.webp', 0, 1722521508, 1722526634, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (101, 13, 3014716, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/5c535a2f51120b4ec9939cf98d274f60.webp', '年画娃娃_1c6c56875527459cb1436b9398f6aa94.webp', 0, 1722521508, 1722526636, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (102, 13, 3014711, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/3e3596e15766bb5f2f20c1a1bb54e80b.webp', '年画娃娃_33aaf9e33b7b478f97db210d71cddb46.webp', 0, 1722521508, 1722526637, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (103, 13, 3014705, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/09c661e452d5c27b07a497f2e1383235.webp', '年画娃娃_82f1ab12464d4cd2b9428c607c567bd7.webp', 0, 1722521508, 1722526638, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (104, 13, 3014717, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/1865f41f7363b95244f3534f5fad5382.webp', '年画娃娃_b65cb026a0464701bbdb9713fe7e71d6.webp', 0, 1722521508, 1722526640, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (105, 13, 3014718, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/f3c96611ae057114bcb3aecb8f5fa81d.webp', '年画娃娃_212e8cfe5c4a474ba4be7585f7be142e.webp', 0, 1722521508, 1722526641, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (106, 13, 3014706, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/5f6ee4e5c1af514189b3aa24c4536a14.webp', '年画娃娃_4537ccf1f95a4c84a722e7ff12779976.webp', 0, 1722521508, 1722526642, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (107, 13, 3014713, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/4731d94c52cae70810e5051339607cc1.webp', '年画娃娃_7477a7fb71ca452eba970fae217442d7.webp', 0, 1722521508, 1722526644, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (108, 13, 3014712, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/6985a4692f9b162066131a7d41194c7e.webp', '年画娃娃_96935c353dde4bbba885262e67820b88.webp', 0, 1722521508, 1722526645, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (109, 13, 3014722, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/b5182c0f8616bc8c12104f34a21a8259.webp', '年画娃娃_866d3e39bd0c411993c0707a98d28fc5.webp', 0, 1722521508, 1722526647, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (110, 13, 3014724, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/8f81bedfcebd110b6af49768d9aad71c.webp', '年画娃娃_e32cb8b9815245569f751c76cc4e0fd8.webp', 0, 1722521508, 1722526648, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (111, 13, 3014710, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/46d0ba7b5fb9773ce2cd81af77e55b5c.webp', '年画娃娃_94015b15fdc04b618c2bac77ad5fce54.webp', 0, 1722521508, 1722526650, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (112, 14, 2842543, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/b36170fbac246984563482a898d1c32d.webp', '剩单快乐_6889319b43fa487bbdd42700d429e092.webp', 0, 1722521508, 1722526651, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (113, 14, 2842396, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/14b218d90c350f4462fef47f3f8a39ba.webp', '剩单快乐_467585c0a177411c93d11843a9da32fb.webp', 0, 1722521508, 1722526654, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (114, 14, 2842394, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/f0e720b2b673f28e454b71563c7b8637.webp', '剩单快乐_318f02bd18f94834881319281d541bc8.webp', 0, 1722521508, 1722526655, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (115, 14, 2842404, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/00e6d167f7b5faed34b07cbb8cd21748.webp', '剩单快乐_d290340240944268ac7e79b2ae374b19.webp', 0, 1722521508, 1722526658, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (116, 14, 2842400, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/380f6224e52537eeb8977a9793d1188e.webp', '剩单快乐_3d88c7b77b04452ca8bf3cd2e0f584b8.webp', 0, 1722521508, 1722526660, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (117, 14, 2842402, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/4f0ab44d002dfb23325dc57815521c49.webp', '剩单快乐_3f14e6279152485c8799cec33775bbfc.webp', 0, 1722521508, 1722526662, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (118, 14, 2842405, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/8bff93f7a4df222d06cbc13f998f3d13.webp', '剩单快乐_8e48031a844d44689af7210a46cc8cb5.webp', 0, 1722521508, 1722526664, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (119, 14, 2842395, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/ba45463e07c7023c71c0b2bb33a581cb.webp', '剩单快乐_e52023d2327e459fbeb29c4f531a986d.webp', 0, 1722521508, 1722526666, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (120, 14, 2842408, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/cd163e04b048910c8e97a628aa67506e.webp', '剩单快乐_143907f04fc549de934067a03e613ef9.webp', 0, 1722521508, 1722526668, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (121, 14, 2842406, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/03fe8601d0a2a88b37dac89676297c99.webp', '剩单快乐_a5a5e0f1d02340d2801e46b5a2a9f8a0.webp', 0, 1722521508, 1722526670, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (122, 14, 2842397, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/6f712236c3b5588f5b8832f1b9293dfd.webp', '剩单快乐_90e62554dd804495a881c4f3f21da03f.webp', 0, 1722521508, 1722526671, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (123, 14, 2842398, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/1f50fb2429002262ebd098c6ab1bae7d.webp', '剩单快乐_e60b742017f24755a982e086de2cd4e0.webp', 0, 1722521508, 1722526673, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (124, 14, 2842399, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/9d70502d7685da9ac4f9024b3faa6289.webp', '剩单快乐_bff05b70018e4dcaad80bec644e09321.webp', 0, 1722521508, 1722526674, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (125, 14, 2842392, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/ff34250448936b5151850cfc9a51e464.webp', '剩单快乐_5ba015c51cc94454a8bfae06c2f10b6f.webp', 0, 1722521508, 1722526675, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (126, 14, 2842401, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/b57dbbf0115a7f928ef69c0700ed2bd6.webp', '剩单快乐_5054b67e1c6b42b5a64b1a5b161c7f42.webp', 0, 1722521508, 1722526678, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (127, 14, 2842403, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/531845a145ab2438f11bf6a87a16b66a.webp', '剩单快乐_7bfed9126286416f846e55f9080dfbc3.webp', 0, 1722521508, 1722526679, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (128, 14, 2842393, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/9a2a9c8f4f4834b1efe3182bc6fc0000.webp', '剩单快乐_c540baeec2fc4073b90929c38a32a74b.webp', 0, 1722521508, 1722526680, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (129, 15, 2842447, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/a81dfb96568c9a3868bd11125d1c241c.webp', '玩梗不闻会君_3280d2b356284b43816828ea09dd05a8.webp', 0, 1722521508, 1722526682, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (130, 15, 2842450, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/a904ca464dc03aadc37e5582c4d80da6.webp', '玩梗不闻会君_57c6c3aa739749c78f2727e577993fc1.webp', 0, 1722521508, 1722526683, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (131, 15, 2842423, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/5d4184bfa6904b8a208018d5dbae7438.webp', '玩梗不闻会君_d43af2c4617044d3bf79febcfd9d539e.webp', 0, 1722521508, 1722526685, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (132, 15, 2842426, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/b74c8a3af23af0455109a200f266b965.webp', '玩梗不闻会君_ed42f097097d4b5eadde88d675be30e0.webp', 0, 1722521508, 1722526686, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (133, 15, 2842445, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/6e4c2e5d5d895f23f50097019846b04a.webp', '玩梗不闻会君_c9ebbd300a3d4d49a6d42a4d50d2f27b.webp', 0, 1722521508, 1722526687, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (134, 15, 2842440, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/9143402918ccdee2cc5d0046adaae988.webp', '玩梗不闻会君_356ba2acd9a948549baa0834c491d474.webp', 0, 1722521508, 1722526688, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (135, 15, 2842438, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/b2032e1dc28a92167c0ca65ee4837474.webp', '玩梗不闻会君_90f6522eb5b741219ed85f38fcd46977.webp', 0, 1722521508, 1722526690, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (136, 15, 2842437, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/ffbcc94c6f2d77d8d07bc47afa1ec7d2.webp', '玩梗不闻会君_58fb76e5f3764e70ad36215a936adea7.webp', 0, 1722521508, 1722526692, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (137, 15, 2842449, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/9eebb4964e635684c8458aac851e0e0d.webp', '玩梗不闻会君_02054f3f09454166aff3c42eda7d4613.webp', 0, 1722521508, 1722526694, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (138, 15, 2842448, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/ffc94860e15c7b794c804c065c3ddcbe.webp', '玩梗不闻会君_d6a3c12ef0334bec900df4fef9bdf2e8.webp', 0, 1722521508, 1722526696, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (139, 15, 2842446, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/817b1797f1e3ad5d4f6e5303915f69eb.webp', '玩梗不闻会君_72512ada9d8c407a9ac73aff32fddc11.webp', 0, 1722521508, 1722526697, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (140, 15, 2842444, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/819cdb017e534fc33e18105e1c988f26.webp', '玩梗不闻会君_648d184b6085479ba42b2647857d4014.webp', 0, 1722521508, 1722526698, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (141, 15, 2842434, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/0382956390dbffc6dd3d15681bc33ba5.webp', '玩梗不闻会君_71b27e0775ef41749e330e996118dd61.webp', 0, 1722521508, 1722526700, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (142, 15, 2842441, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/1421dd0634ff7970bce9305832b8b418.webp', '玩梗不闻会君_88c33d032f1442a580d623fdf4f241b7.webp', 0, 1722521508, 1722526701, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (143, 15, 2842443, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/7ab9f9a527c22f3d14696496a3157f86.webp', '玩梗不闻会君_8a4287166c1b46708b10db4d7218bd07.webp', 0, 1722521508, 1722526703, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (144, 15, 2842430, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/862161e40996791917d4fa4c2e826a93.webp', '玩梗不闻会君_0713430a655c44ebbf85ecce29e5e086.webp', 0, 1722521508, 1722526704, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (145, 15, 2842544, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/adb97c3048c92f227063da65cb1eaa77.webp', '玩梗不闻会君_d367925468d645c59aa313897a99d5f9.webp', 0, 1722521508, 1722526705, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (146, 15, 2842435, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/12eb7e2728ec3ea636f8818156e031bf.webp', '玩梗不闻会君_e7b6d54bd30e411a8a81f04fe875fc14.webp', 0, 1722521508, 1722526707, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (147, 15, 2842424, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/5f30703b84796d68883850a986842353.webp', '玩梗不闻会君_1a680f207c0f44699906d0700aba4e3c.webp', 0, 1722521509, 1722526708, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (148, 15, 2842425, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/b3406134bf8d67f3fa4bafbe0fa40c0f.webp', '玩梗不闻会君_06cdb844261b4ab78b5dbb442f6ec1b5.webp', 0, 1722521509, 1722526710, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (149, 15, 2842427, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/e2785735590273d0d679e26397c9bb5d.webp', '玩梗不闻会君_3f224093272f4734bdea39dc3ea46249.webp', 0, 1722521509, 1722526711, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (150, 15, 2842429, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/801074d8d5b794a3f3a6c84b8f5e687c.webp', '玩梗不闻会君_26deabd3363c4818b555bd2bc4985062.webp', 0, 1722521509, 1722526712, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (151, 15, 2842431, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/41b73d3749c982406712daccdc57a89b.webp', '玩梗不闻会君_9e4148215c3c4da18e9d92f154bbedbd.webp', 0, 1722521509, 1722526714, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (152, 15, 2842432, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/4faac941e97e2e7455a9874178794864.webp', '玩梗不闻会君_2184f81d7e484085b3f0da7303e396f9.webp', 0, 1722521509, 1722526716, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (153, 15, 2842442, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/45ba7898802059483de569bc0fa844d3.webp', '玩梗不闻会君_f4a8de3195cb4f2b99f1bbb2d18c0242.webp', 0, 1722521509, 1722526718, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (154, 15, 2842433, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/c820ba970220bdfdf78b8be02bc79bbe.webp', '玩梗不闻会君_d38beace01904876ba5f046bbcc197b3.webp', 0, 1722521509, 1722526720, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (155, 15, 2842439, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/9792143acc8803c954d9564e46bdd8d8.webp', '玩梗不闻会君_73796244f7a747628be06b367cefeead.webp', 0, 1722521509, 1722526722, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (156, 15, 2842422, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/1e83c7b87ab4b86bf45aead021072d33.webp', '玩梗不闻会君_6c6ae4fa99674f9fb054917f419f4cef.webp', 0, 1722521509, 1722526723, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (157, 15, 2842436, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/f95a51b3d9943391882daedfc20bf349.webp', '玩梗不闻会君_7331adda2398494a89dd115f6921072f.webp', 0, 1722521509, 1722526725, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (158, 16, 2842413, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/40426049e56692d9dbf0729488b6f916.webp', '暖冬胶片_5abf641064474bb5a6dca1e9972bfb66.webp', 0, 1722521509, 1722526727, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (159, 16, 2842410, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/517f7e5d3baebaf9c804d9c2d88f3136.webp', '暖冬胶片_232b42965ab240a8ab6d4f437972d78c.webp', 0, 1722521509, 1722526728, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (160, 16, 2842409, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/2732a631564618309ca323534171f78e.webp', '暖冬胶片_2f01f965cb8e45cf94a0a9d87054ba0b.webp', 0, 1722521509, 1722526730, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (161, 16, 2842421, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/ad6b5bbaf7cea0d50da9b20bcc54d5e4.webp', '暖冬胶片_db45b4e9a5b6448ea3734b55e8f3f86f.webp', 0, 1722521509, 1722526732, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (162, 16, 2842412, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/31cf20d3f3ad12fa2b380a22528f0dfc.webp', '暖冬胶片_ac5ea0824c72452badab08ac4c5fea28.webp', 0, 1722521509, 1722526733, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (163, 16, 2842420, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/042ef728c77b14d94ecc59a5c07e0dbd.webp', '暖冬胶片_5c850b0bf79c4058976f726c6b7cbec8.webp', 0, 1722521509, 1722526735, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (164, 16, 2842414, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/a12f1f48ffbe512100a8b187513a52a8.webp', '暖冬胶片_61f72eb9cf4c454f82bf97368eee4e5a.webp', 0, 1722521509, 1722526736, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (165, 16, 2842416, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/5d3995f6b37f1a55a79cce5753ff050b.webp', '暖冬胶片_b67cd47e84514a22993bc875ddcd5004.webp', 0, 1722521509, 1722526738, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (166, 16, 2842419, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/8453d51317e10b88178165f618d7aed0.webp', '暖冬胶片_2eb244a0e40f429894dd02e623661935.webp', 0, 1722521509, 1722526739, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (167, 16, 2842417, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/27601a336df8cfd7f10ec7e8df654c52.webp', '暖冬胶片_0e82c2ce9b34496997ce67101a6394ac.webp', 0, 1722521509, 1722526740, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (168, 16, 2842411, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/521b57d625112c3f96f19a0c5f8d6af1.webp', '暖冬胶片_4f475a007d3b4a52bcef426c0768081b.webp', 0, 1722521509, 1722526742, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (169, 16, 2842415, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/858f1fbf85603f94d2d23b1b1bf6d68d.webp', '暖冬胶片_0af63c0d04854298b2a2281b0845972e.webp', 0, 1722521509, 1722526744, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (170, 16, 2842418, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/bb847d6893d32723c28dff7875f1ac4c.webp', '暖冬胶片_b3075345369548898a4f96b4c1bcf19a.webp', 0, 1722521509, 1722526746, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (171, 17, 2842492, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/64826d58a7a40e4ec533fd8322c883d5.webp', '高质量男女_97c29e32608a486180b521baade914f9.webp', 0, 1722521509, 1722526749, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (172, 17, 2842483, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/7c5e870703fc0ddca30d76260865fde7.webp', '高质量男女_36b4e968c88c4b86bd65b04e447ec360.webp', 0, 1722521509, 1722526750, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (173, 17, 2842489, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/c3bc3d0974234af17347196ee998f2f9.webp', '高质量男女_23b1f07c933a466eb1723526c9bd0e5c.webp', 0, 1722521509, 1722526752, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (174, 17, 2842488, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/1a628027f252cdb157d39391df2c1f6d.webp', '高质量男女_aaa3b478d42c483b97245f2b730c6229.webp', 0, 1722521509, 1722526754, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (175, 17, 2842486, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/e5867bf1e865bfd74058ea502bf1a71b.webp', '高质量男女_ef07868d45224942b25b27b90db08e37.webp', 0, 1722521509, 1722526756, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (176, 17, 2842490, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/4fe29cfd4762eae92911535210d52572.webp', '高质量男女_ca07f1594e794885b3e9b494d2ae76c7.webp', 0, 1722521509, 1722526758, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (177, 17, 2842491, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/40ce2ebf2d683d3d1dd4a88176081842.webp', '高质量男女_612617747de6479f9a41eb55bc178711.webp', 0, 1722521509, 1722526760, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (178, 17, 2842493, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/be0220c4c5e3a74bfebfd0837a047522.webp', '高质量男女_44f2f9100a834d8389d8d3cf7de5f577.webp', 0, 1722521509, 1722526762, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (179, 17, 2842484, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/2d009315c7f0f8e3fe6784de665091ee.webp', '高质量男女_56434506bd4d4e9c8f380fd4b62d5d24.webp', 0, 1722521509, 1722526763, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (180, 17, 2842485, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/d11157aeacb8af3f78ac48b313611da3.webp', '高质量男女_30e183142d774dca90ec7b1b151c9d9a.webp', 0, 1722521509, 1722526765, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (181, 17, 2842494, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/3aa9281b4cca748fd890751d06b5c884.webp', '高质量男女_4cac5729a9244800bbe92e9a63a7aa4e.webp', 0, 1722521509, 1722526767, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (182, 18, 2842459, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/c7886a55070cb4d62695e4586b3fd6ad.webp', '高管证件照_2ac15e640e054c0cb62e6d4af6cd4a5c.webp', 0, 1722521509, 1722526768, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (183, 18, 2842457, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/ad7d14e17e947a9d27580eb7f72e661e.webp', '高管证件照_2555d25ecfc54779b34f4aa31fa3e4d7.webp', 0, 1722521509, 1722526770, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (184, 18, 2842458, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/fffdb9657ca3d54ffec31a924c8a0edd.webp', '高管证件照_f26eb2d75662446aaf6ec19d90153c1c.webp', 0, 1722521509, 1722526771, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (185, 23, 2842455, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/1a9e2e5e866b3bf83f7b46573dda6a87.webp', '高管证件照_a09c9a7641524815b4f9d069a74eafeb.webp', 0, 1722521509, 1722526772, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (186, 18, 2842476, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/24c7d0ba0525d0795d17d1321f593fc7.webp', '高管证件照_3d7771af02c94095a564ac3961f8c3b7.webp', 0, 1722521509, 1722526774, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (187, 23, 2842468, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/26708832765f646709ae770f0f19ddb5.webp', '高管证件照_1e7ca3d241e44aca84495f610d61cda8.webp', 0, 1722521509, 1722526775, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (188, 18, 2842460, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/f6adb187ec818c6ed4c3c6c0fd52bed3.webp', '高管证件照_b3e89ff12521407984d284e6e40c9ee7.webp', 0, 1722521509, 1722526776, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (189, 23, 2842472, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/e33ff0339aa3d17fd0a8e477f7b9d530.webp', '高管证件照_e1f463c7ed294f3f84072ce2ab37ec87.webp', 0, 1722521509, 1722526778, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (190, 23, 2842453, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/1d71d662f97b8374e139c9c3933c0b50.webp', '高管证件照_9040b7844d2a4ae0b9e66ef5f34e98f4.webp', 0, 1722521509, 1722526779, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (191, 23, 2842451, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/dee88f5a4c4eced3204b02afe114f325.webp', '高管证件照_413c8b5c09e849929f206bfbd5486dea.webp', 0, 1722521509, 1722526781, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (192, 23, 2842474, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/af72c990dc6e0fdd53191f4fa9eeb2a7.webp', '高管证件照_f5bd4e7a43894215a4e7c04691408746.webp', 0, 1722521509, 1722526782, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (193, 18, 2842461, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/989d2f3c025019e246af46bb958b1f1e.webp', '高管证件照_f822a53cc9dd44338f975557667a04de.webp', 0, 1722521509, 1722526785, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (194, 23, 2842469, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/a2652f5c988816bfd275a024d4cd526e.webp', '高管证件照_03c4976ef4c147ff9704f2bb568b6317.webp', 0, 1722521509, 1722526787, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (195, 23, 2842454, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/e9e6abb30503ad2651555cdc2aedc509.webp', '高管证件照_b0e18b93385a49fb83006325a82cf8d3.webp', 0, 1722521509, 1722526788, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (196, 23, 2842473, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/77d9bd847350ce8f99c48056f8ad0ed8.webp', '高管证件照_d42e270390734caf8785a43e1a7d1f40.webp', 0, 1722521509, 1722526789, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (197, 23, 2842481, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/c5844ef32eb1ea299fd12ad319ddcd9e.webp', '高管证件照_477fa1526ee24e8081ce2693812a2068.webp', 0, 1722521509, 1722526792, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (198, 23, 2842456, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/a8114bb078dbf56b8ba504db2d8f9a86.webp', '高管证件照_9a2801b76a6842b58f1dbbf5127a49ce.webp', 0, 1722521509, 1722526794, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (199, 23, 2842471, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/fe7203cfbc7d4f6b9d68b240492f4565.webp', '高管证件照_8e820a2824e74fcfa83dd63f466b677a.webp', 0, 1722521509, 1722526797, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (200, 23, 2842480, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/e722546db78f692230dcaa2663e6aba5.webp', '高管证件照_e5e8aac5022d49c1a36786e7cdbcfae0.webp', 0, 1722521509, 1722526798, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (201, 23, 2842475, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/beb74c0c8390ba4cc5a1dcab0472e3a1.webp', '高管证件照_89ebd257df7d466c9d731efd280b6bfc.webp', 0, 1722521509, 1722526800, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (202, 23, 2842482, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/0ea657bea132e144f47b94ed8751bf86.webp', '高管证件照_780dfe71c3d54f56a55d24e527dd9f0d.webp', 0, 1722521509, 1722526801, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (203, 23, 2842452, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/f218222b4c7313af3bcc42e6de371b36.webp', '高管证件照_0881083136894ac1b3e75e37eccf9088.webp', 0, 1722521509, 1722526802, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (204, 23, 2842470, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/b9d9f4c5bb1dabd6572fdd28fcb51ed0.webp', '高管证件照_4380a5e3883c4532bac693870d394d85.webp', 0, 1722521509, 1722526805, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (205, 23, 2842467, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/ea7d83df24a3d942289e95794cf8bc69.webp', '高管证件照_1483aedd84b44f5d99d99968f980c42d.webp', 0, 1722521509, 1722526806, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (206, 23, 2842465, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/01b1d68f96e08118067fb4790a7374e5.webp', '高管证件照_648138fa4b204132b3d4ab4f08c8ec32.webp', 0, 1722521509, 1722526808, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (207, 23, 2842464, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/3893415b00b364aa08fc9b4e250e7c01.webp', '高管证件照_12a994b51e314adda5fb3d25ade31b27.webp', 0, 1722521509, 1722526810, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (208, 23, 2842478, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/f0f580971eab790cddbc7d912becd053.webp', '高管证件照_655316622ea84897bc5540b2cef4b063.webp', 0, 1722521509, 1722526811, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (209, 23, 2842462, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/536f576b3b806987ae8297cc97b1b383.webp', '高管证件照_da59c6b79a3444f7bc63d6768e853698.webp', 0, 1722521509, 1722526813, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (210, 23, 2842477, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/54b0da40ae1fe8e4b147043e4074d81b.webp', '高管证件照_04af200aadc34036a28042a97dc219ff.webp', 0, 1722521509, 1722526815, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (211, 23, 2842463, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/8204d03a6e46ab8d63914d1434bb5976.webp', '高管证件照_5210089fa29642b1bc1799f6d27223c6.webp', 0, 1722521509, 1722526816, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (212, 23, 2842466, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/27a3e787eb6d79e3da83e8d2856e0e63.webp', '高管证件照_252478b872d04973ab31006c114343bc.webp', 0, 1722521509, 1722526817, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (213, 23, 2842479, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/f17fd78ccc7389dcb2dad9a7ff0851f8.webp', '高管证件照_aa56131265674de39539eb8598752c0a.webp', 0, 1722521509, 1722526819, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (214, 19, 234724, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/ed0152c65a9ba1487e84da7665cd9815.webp', '科幻_ecd59c11d729494cb1ab91a3589e8700.webp', 0, 1722521509, 1722526820, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (215, 19, 2463800, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/31883510e985a92cb4a17e1b52688666.webp', '科幻_181b3316e15b42b0baa3356ba59bcd61.png', 0, 1722521509, 1722526822, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (216, 19, 2463813, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/69620edca5cd578fc9e005c9d5272508.webp', '科幻_de77001b663f4f3c9319d08526c03e48.png', 0, 1722521509, 1722526823, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (217, 19, 2463825, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/a24362849266a109a377b6afe6d62347.webp', '科幻_1d72da721fb2406191a8f2aeaa2eae9c.png', 0, 1722521509, 1722526825, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (218, 20, 2463819, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/21bcfcffce82f52665cbf05c130d43a7.webp', '剧情_33bb9aea7de448cea11ce4ea400a1199.png', 0, 1722521510, 1722526826, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (219, 20, 2463775, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/6782f9d096ee2c8f18aac3833eadade6.webp', '剧情_06bbd2f278fd4b81bdae568461c14dd6.png', 0, 1722521510, 1722526830, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (220, 21, 234719, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/26be986a66335191e72ac185bf9f5e96.webp', '奇幻_887ea9ffc313477abff6f7deccb7e807.webp', 0, 1722521510, 1722526831, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (221, 21, 2463807, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/9b73660d9de6a9aef0cc4a0cb3056066.webp', '奇幻_8a08ebaa539d4a09adb3c1f1e1f2d7ad.png', 0, 1722521510, 1722526833, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (222, 21, 2463787, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/6836e2e24bc5a4aa3a9313db60013f1c.webp', '奇幻_03a8418e7da74078ab973b712376fb2a.png', 0, 1722521510, 1722526834, null);
INSERT INTO ai_luna_template (id, group_id, up_template_id, image_url, name, sort, create_time, update_time, delete_time) VALUES (223, 22, 2463765, 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/system/template/image/3b23319bca98f82c6429691051ce367c.webp', '历史剧_8ec3289c6e03418eaf5ba2c2a912db12.png', 0, 1722521510, 1722526837, null);

# todo 模板管理菜单路由、表结构
CREATE TABLE `ai_swap_template`
(
    `id`             int unsigned                                                  NOT NULL AUTO_INCREMENT COMMENT '主键',
    `name`           varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
    `is_collection`  tinyint unsigned                                              NOT NULL DEFAULT '0' COMMENT '是否合辑类型',
    `up_template_id` bigint unsigned                                               NOT NULL DEFAULT '0' COMMENT '上游模板ID',
    `image_url`      text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NOT NULL COMMENT '封面图/目标图地址',
    `status`         tinyint unsigned                                              NOT NULL DEFAULT '0' COMMENT '状态',
    `create_time`    int                                                                    DEFAULT NULL COMMENT '创建时间',
    `update_time`    int                                                                    DEFAULT NULL COMMENT '更新时间',
    `delete_time`    int                                                                    DEFAULT NULL COMMENT '删除时间',
    `face_list`      json                                                                   DEFAULT NULL COMMENT '人脸列表',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT ='换脸模板';

CREATE TABLE `ai_swap_template_collection_relation`
(
    `id`            int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
    `template_id`   int unsigned NOT NULL DEFAULT '0' COMMENT '模板ID',
    `collection_id` int unsigned NOT NULL DEFAULT '0' COMMENT '合辑ID',
    `create_time`   int                   DEFAULT NULL COMMENT '创建时间',
    `update_time`   int                   DEFAULT NULL COMMENT '更新时间',
    `delete_time`   int                   DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 18
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT ='换脸模板合辑关联表';

CREATE TABLE `ai_swap_template_group`
(
    `id`            int unsigned                                                  NOT NULL AUTO_INCREMENT COMMENT '主键',
    `name`          varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
    `is_collection` tinyint unsigned                                              NOT NULL DEFAULT '0' COMMENT '是否合辑类型',
    `status`        tinyint unsigned                                              NOT NULL DEFAULT '0' COMMENT '状态',
    `create_time`   int                                                                    DEFAULT NULL COMMENT '创建时间',
    `update_time`   int                                                                    DEFAULT NULL COMMENT '更新时间',
    `delete_time`   int                                                                    DEFAULT NULL COMMENT '删除时间',
    `is_group_swap` tinyint unsigned                                              NOT NULL DEFAULT '0' COMMENT '换脸模式。0=单人 1=多人',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT ='换脸模板分组';

CREATE TABLE `ai_swap_template_group_relation`
(
    `id`          int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
    `template_id` int unsigned NOT NULL DEFAULT '0' COMMENT '模板ID',
    `group_id`    int unsigned NOT NULL DEFAULT '0' COMMENT '分组ID',
    `create_time` int                   DEFAULT NULL COMMENT '创建时间',
    `update_time` int                   DEFAULT NULL COMMENT '更新时间',
    `delete_time` int                   DEFAULT NULL COMMENT '删除时间',
    `sort`        int unsigned NOT NULL DEFAULT '0' COMMENT '排序',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT ='换脸模板分组关联表';

CREATE TABLE `ai_swap_strategy`
(
    `id`                int unsigned                                                  NOT NULL AUTO_INCREMENT COMMENT '主键',
    `name`              varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
    `allow_cross_group` tinyint unsigned                                              NOT NULL DEFAULT '0' COMMENT '是否允许跨组',
    `is_group_swap`     tinyint unsigned                                              NOT NULL DEFAULT '0' COMMENT '换脸模式。0=单人 1=多人',
    `template_scope`    tinyint unsigned                                              NOT NULL DEFAULT '0' COMMENT '模板限制。0=不限制 1=限制单张模板 2=限制合辑模板',
    `status`            tinyint unsigned                                              NOT NULL DEFAULT '0' COMMENT '状态',
    `create_time`       int                                                                    DEFAULT NULL COMMENT '创建时间',
    `update_time`       int                                                                    DEFAULT NULL COMMENT '更新时间',
    `delete_time`       int                                                                    DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT ='换脸玩法';

alter table ai_swap_template add index idx_name (name);

CREATE TABLE `ai_digital_avatar`
(
    `id`           int                                                           NOT NULL AUTO_INCREMENT COMMENT 'id',
    `user_id`      int                                                           NOT NULL COMMENT '用户id',
    `image_url`    varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '原图URL',
    `face_url`     varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '脸部URL',
    `file_id`      bigint                                                        NOT NULL COMMENT '本系统文件ID',
    `up_file_id`   bigint                                                        NOT NULL COMMENT '上游文件ID',
    `up_face_id`   bigint                                                        NOT NULL COMMENT '上游人脸ID',
    `up_face_data` json DEFAULT NULL COMMENT '上游人脸区域数据',
    `create_time`  int  DEFAULT NULL COMMENT '创建时间',
    `update_time`  int  DEFAULT NULL COMMENT '更新时间',
    PRIMARY KEY (`id`) USING BTREE,
    KEY `idx_user_id` (`user_id`) USING BTREE,
    KEY `idx_up_face_id` (`up_face_id`) USING BTREE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci
  ROW_FORMAT = DYNAMIC COMMENT ='数字分身表';

CREATE TABLE `ai_swap_task`
(
    `id`            int                                                          NOT NULL AUTO_INCREMENT COMMENT 'id',
    `user_id`       int                                                          NOT NULL COMMENT '用户id',
    `strategy_id`   int                                                          NOT NULL COMMENT '玩法策略id',
    `up_task_id`    varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '上游任务ID',
    `draw_number`   tinyint unsigned                                             NOT NULL DEFAULT '0' COMMENT '作图总数',
    `face_mapping`  json                                                                  DEFAULT NULL COMMENT '人脸映射参数',
    `user_draft`    json                                                                  DEFAULT NULL COMMENT '用户作图的草稿信息。JSON格式',
    `upstream_resp` json                                                                  DEFAULT NULL COMMENT '上游系统的返回。JSON格式',
    `result_images` json                                                                  DEFAULT NULL COMMENT '生成结果多张图片URL。JSON格式',
    `status`        tinyint(1)                                                   NOT NULL DEFAULT '0' COMMENT '任务状态：0=默认 1=处理中 2=成功 3=失败',
    `error_msg`     varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '失败信息',
    `create_time`   int                                                                   DEFAULT NULL COMMENT '创建时间',
    `update_time`   int                                                                   DEFAULT NULL COMMENT '更新时间',
    `delete_time`   int                                                                   DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE,
    KEY `idx_user_id` (`user_id`) USING BTREE,
    KEY `idx_task_id` (`up_task_id`) USING BTREE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci
  ROW_FORMAT = DYNAMIC COMMENT ='换脸作图任务';
