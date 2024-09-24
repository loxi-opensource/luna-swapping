<?php

namespace app\common\logic;

use app\common\enum\FileEnum;
use app\common\service\ImageProcessingService;
use app\common\service\UploadService;

class ImageLogic
{
    public static function mergeRemoteImages($imageUrl1, $imageUrl2, $userId = 0, $source = FileEnum::SOURCE_USER)
    {
        $dirPath = runtime_path('temp');
        // 创建文件夹
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0755, true);
        }
        $localFilePath = $dirPath . md5($imageUrl1 . $imageUrl2) . '.png';
        $mergeSucceed = ImageProcessingService::mergeImages($imageUrl1, $imageUrl2, $localFilePath);
        if (!$mergeSucceed) {
            return false;
        }
        $ossResult = UploadService::uploadImageByLocalPath($localFilePath, $userId, $source);
        return [$ossResult, $localFilePath];
    }
}
