<?php

namespace app\common\service;

use Intervention\Image\ImageManagerStatic as Image;

class ImageProcessingService
{
    public static function mergeImages($image1Path, $image2Path, $outputPath)
    {
        $image1 = Image::make($image1Path);
        $image1Width = $image1->width();
        $image1Height = $image1->height();

        $image2 = Image::make($image2Path);
        $image2Width = $image2->width();
        $image2Height = $image2->height();

        $canvasWidth = $image1Width + $image2Width;
        $canvasHeight = max($image1Height, $image2Height);

        $canvas = Image::canvas($canvasWidth, $canvasHeight);

        $canvas->insert($image1, 'top-left');
        $canvas->insert($image2, 'bottom-right');

        $canvas->save($outputPath);
        return file_exists($outputPath);
    }
}
