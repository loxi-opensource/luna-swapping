<?php
declare (strict_types=1);

namespace app\command;

use app\common\model\LunaTemplate;
use app\common\service\ConfigService;
use app\common\service\FileService;
use app\common\service\storage\Driver as StorageDriver;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\helper\Str;

class SyncImageFromAws extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('test')
            ->setDescription('the test command');
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $output->writeln('Test');
        $config = [
            'default' => 'aliyun',
            'engine' => ConfigService::get('storage'),
        ];
        $driver = new StorageDriver($config);
        LunaTemplate::chunk(50, function ($list) use ($driver, $output, $config) {
            foreach ($list as $item) {
                $output->writeln('process ' . $item['id']);
                if (!Str::contains($item['image_url'], 'cloudfront')) {
                    $output->writeln('skip');
                    continue;
                }
                $url = $item['image_url'];
                $ossKey = 'uploads/system/template/image/' . md5($url) . '.webp';
                $res = $driver->fetch($url, $ossKey);
                if (!$res) {
                    $output->writeln("fetch image fail: " . $driver->getError());
                    continue;
                }
                $domain = $config['engine']['aliyun']['domain'];
                $ossUrl = FileService::format($domain, $ossKey);
                $item->image_url = $ossUrl;
                $item->save();
                $output->writeln('success: ' . $ossUrl);
            }
        });
    }
}

