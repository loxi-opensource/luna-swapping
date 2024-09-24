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

namespace app\api\logic;

use app\common\enum\DrawEnum;
use app\common\enum\user\AccountLogEnum;
use app\common\logic\AccountLogLogic;
use app\common\logic\BaseLogic;
use app\common\model\draw\DrawRecords;
use app\common\model\user\User;
use app\common\service\ConfigService;
use app\common\service\draw\DrawDriver;
use app\common\service\FileService;
use app\common\service\openai\ChatGptService;
use app\common\service\storage\Driver as StorageDriver;
use think\facade\Db;
use think\facade\Log;


/**
 * 绘图逻辑
 * Class DrawLogic
 * @package app\api\logic
 */
class DrawLogic extends BaseLogic
{
    /**
     * @notes 校验能否绘画
     * @param $userId
     * @return bool|string
     * @author 段誉
     * @date 2023/6/21 14:29
     */
    public static function checkAbleDraw($userId, $needBalance)
    {
        $user = User::where('id', $userId)->findOrEmpty()->toArray();
        if ($user['balance_draw'] < $needBalance) {
            return '余额不足';
        }
        return true;
    }

    /**
     * @notes 绘画余额处理
     * @param $userId
     * @param $usedToken
     * @param $changeType
     * @author 段誉
     * @date 2023/7/24 11:35
     */
    public static function drawBalanceHandle($userId, $usedToken, $changeType)
    {
        // 用户信息
        $user = User::findOrEmpty($userId);

        // $action 变动类型 $totalDraw 累计绘画消费 $balanceDraw 绘画余额
        if (in_array($changeType, AccountLogEnum::DRAW_INC)) {
            $action = AccountLogEnum::INC;
            $totalDraw = $user->total_draw - $usedToken;
            $balanceDraw = $user->balance_draw + $usedToken;
        } else {
            $action = AccountLogEnum::DEC;
            $totalDraw = $user->total_draw + $usedToken;
            $balanceDraw = $user->balance_draw - $usedToken;
        }
        $totalDraw = $totalDraw < 0 ? 0 : $totalDraw;

        $user->balance_draw = $balanceDraw;
        $user->total_draw = $totalDraw;
        $user->save();

        // 记录账户流水
        AccountLogLogic::add($userId, $changeType, $action, $usedToken);
    }

    /**
     * @notes 下载图片
     * @param $downloadUrl
     * @return string
     * @author 段誉
     * @date 2023/8/3 16:21
     */
    public static function downloadImage($downloadUrl): string
    {
        if (empty($downloadUrl)) {
            return '';
        }

        try {
            // 存储引擎
            $config = [
                'default' => ConfigService::get('storage', 'default', 'local'),
                'engine' => ConfigService::get('storage')
            ];

            // 文件名称
            $fileName = md5($downloadUrl) . '.png';

            // 第三方存储
            if ($config['default'] == 'local') {
                // 下载到本地,如果存储为oss时,生成缩略图后删除
                $localPath = download_file($downloadUrl, 'uploads/draw/' . date('Ymd') . '/', $fileName);
            } else {
                $localPath = 'uploads/draw/' . date('Ymd') . '/' . $fileName;
                $StorageDriver = new StorageDriver($config);
                if (!$StorageDriver->fetch($downloadUrl, $localPath)) {
                    throw new \Exception('绘图保存失败:' . $StorageDriver->getError());
                }
            }

            return $localPath;

        } catch (\Exception $e) {
            Log::write('绘图回调下载:' . $e->getMessage() . $e->getLine());
            return "";
        }
    }

    /**
     * @notes 生成缩略图
     * @param $originalImagePath
     * @return string
     * @author 段誉
     * @date 2023/8/4 17:00
     */
    public static function getThumbnail($originalImagePath)
    {
        try {
            // 保存路径
            $saveDir = 'uploads/thumbnail/' . date('Ymd') . '/';
            $fileName = basename($originalImagePath);

            // 缩略图保存路径
            $thumbnailImagePath = public_path() . $saveDir;

            if (!is_dir($thumbnailImagePath)) {
                mkdir($thumbnailImagePath, 0755, true);
            }

            // 缩略图的宽度和高度
            $thumbnailWidth = 200;
            $thumbnailHeight = 200;

            // 创建原始图像资源
            $originalImage = imagecreatefrompng($originalImagePath);

            // 获取原始图像的宽度和高度
            $originalWidth = imagesx($originalImage);
            $originalHeight = imagesy($originalImage);

            // 计算缩略图的宽度和高度
            if ($originalWidth > $originalHeight) {
                $thumbnailHeight = intval($originalHeight * $thumbnailWidth / $originalWidth);
            } else {
                $thumbnailWidth = intval($originalWidth * $thumbnailHeight / $originalHeight);
            }

            // 创建缩略图资源
            $thumbnailImage = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);

            // 将原始图像复制到缩略图中，并进行缩放
            imagecopyresampled($thumbnailImage, $originalImage, 0, 0, 0, 0, $thumbnailWidth, $thumbnailHeight, $originalWidth, $originalHeight);

            // 保存缩略图到文件
            imagepng($thumbnailImage, $thumbnailImagePath . $fileName);

            // 释放资源
            imagedestroy($originalImage);
            imagedestroy($thumbnailImage);

            // 第三方存储的情况
            $config = [
                'default' => ConfigService::get('storage', 'default', 'local'),
                'engine' => ConfigService::get('storage')
            ];
            if ($config['default'] != 'local') {
                // 第三方存储
                $filePath = $saveDir . $fileName;
                $localPath = $thumbnailImagePath . $fileName;
                $StorageDriver = new StorageDriver($config);
                if (!$StorageDriver->fetch($localPath, $filePath)) {
                    throw new \Exception('绘图缩略图保存失败:' . $StorageDriver->getError());
                }
            }

            return $saveDir . $fileName;
        } catch (\Exception $e) {
            Log::write('缩略图生成失败:' . $e->getMessage() . $e->getLine());
            return "";
        }
    }
}
