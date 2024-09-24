<?php
declare (strict_types=1);

namespace app\command;

use app\common\service\aliyun\ImageCropService;
use app\common\service\ImageProcessingService;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class Test extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('test')
            ->setDescription('the test command');
    }

    protected function execute(Input $input, Output $output)
    {
        $output->writeln('start...');
        $originImage = "https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/libraries/oneToOne/86511ca05c4c48c69d6a06ecd0eff4a7.webp";
        $faceList = [
            [
                'h' => 0.188551,
                'w' => 0.177877,
                'x' => 0.357617,
                'y' => 0.0442807,
            ],
            [
                'h' => 0.175551,
                'w' => 0.169204,
                'x' => 0.579925,
                'y' => 0.0911332,
            ],
        ];
        $image = new ImageCropService($originImage);
        dump($image->cropByRatio($faceList[0]['x'], $faceList[0]['y'], $faceList[0]['w'], $faceList[0]['h']));
        dump($image->cropByRatio($faceList[1]['x'], $faceList[1]['y'], $faceList[1]['w'], $faceList[1]['h']));
    }

}

