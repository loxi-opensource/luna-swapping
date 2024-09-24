<?php
declare (strict_types=1);

namespace app\command;

use app\api\logic\LunaDrawLogic;
use app\common\model\LunaTagFilesPic;
use app\common\model\LunaTemplate;
use app\common\model\swap_template\SwapTemplate;
use app\common\model\swap_template\SwapTemplateCollectionRelation;
use app\common\model\user\User;
use app\common\service\luna\LunaDrawService;
use app\common\types\user_draft\Draft;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Db;
use think\helper\Arr;
use think\helper\Str;

class ImportLunaTemplate extends Command
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
//// 示例 JSON 数据
        $jsonData = '{
    "templates": [
        {"id": 589913, "name": "莫吉托_白婚纱_a224600ab6bd4e97ad48ff2e94c6fcf8", "group_name": "莫吉托", "group_id": 5},
        {"id": 589914, "name": "莫吉托_老爷车_78eb1f80b28f4019b966331be176ff30", "group_name": "莫吉托", "group_id": 5}
    ],
    "strategy_id": 1,
    "user_image": {"file_id": 15589, "up_file_id": 2281260, "up_face_id": 1090459}
}';

// 解析 JSON 数据
        $data = json_decode($jsonData, true);

// 创建 DataStructure 实例
        $draft = new Draft($data);

        LunaDrawLogic::submitTaskV3(new User(), $draft);


//        // 示例使用
//        $jsonData = '[
//    {
//        "targetFileId": 3029448,
//        "mapping": {
//            "1088765": "1088760",
//            "1088769": "1088760"
//        }
//    },
//    {
//        "targetFileId": 3029449,
//        "mapping": {
//            "1088770": "1088771"
//        }
//    }
//]';
//
//// 解析 JSON 数据
//        $dataArray = json_decode($jsonData, true);
//        dd(json_encode($dataArray));
//
//// 创建 FaceMappingList 对象
//        $faceMappingList = FaceMappingList::fromArray($dataArray);
//        dd($faceMappingList);
//
//// 查看 FaceMappingList 中的每个对象
//        foreach ($faceMappingList->getFaceMappingList() as $faceMapping) {
//            echo $faceMapping->getTargetFileId() . "\n"; // 输出: 3029448, 3029449
//            dump($faceMapping->getMapping());
//        }
//
//// 转换为数组
//        dump($faceMappingList->toArray());
    }

    protected function importTemplateCollection(Input $input, Output $output)
    {
        // 指令输出
        $output->writeln('导入官方合辑模板');

        $baseQuery = (new LunaTemplate())->where('id', '>', 0);
        $baseQuery->chunk(50, function ($list) use ($output) {
            foreach ($list as $item) {
                $output->writeln('process ' . $item['id']);
                $collectionKeywords = [
                    '多巴胺', '盐系简约', '古风', '泰酷辣',
                    '莫吉托', '跃动青春', '旅游', '科幻', '剧情', '奇幻', '历史剧',
                    '万事兴龙'
                ];
                $matchCollectionName = Arr::first($collectionKeywords, function ($keyword) use ($item) {
                    return Str::contains($item['name'], $keyword);
                });
                if (!$matchCollectionName) {
                    // 单张模板无需创建合辑
                    continue;
                }
                $coverTemplate = SwapTemplate::where('up_template_id', $item['up_template_id'])->find();
                Db::startTrans();
                $newCollection = SwapTemplate::create([
                    'name' => $coverTemplate['name'],
                    'image_url' => $coverTemplate['image_url'],
                    'up_template_id' => 0,
                    'is_collection' => 1,
                    'status' => 1,
                    'is_official' => 1,
                ]);
                $output->writeln('create collection ' . $newCollection['id'] . ' ' . $newCollection['name']);

                $prompt = LunaTagFilesPic::where('tagFileId', $coverTemplate['up_template_id'])->value('promptType');
                $sameCollectionTemplateIds = SwapTemplate::whereIn('up_template_id', function ($query) use ($prompt) {
                    $query->table('ai_luna_tag_files_pic')
                        ->where('promptType', $prompt)
                        ->field('tagFileId');
                })->column('id');

                $output->writeln('same collection template count: ' . count($sameCollectionTemplateIds));

                // $sameCollectionTemplateIds分批处理，批量insert
                $chunkSize = 100;
                $chunks = array_chunk($sameCollectionTemplateIds, $chunkSize);
                foreach ($chunks as $chunk) {
                    $insertData = [];
                    foreach ($chunk as $templateId) {
                        $insertData[] = [
                            'collection_id' => $newCollection['id'],
                            'template_id' => $templateId,
                        ];
                    }
                    SwapTemplateCollectionRelation::insertAll($insertData);
                }

                Db::commit();
            }
        });
    }

    protected function importTemplateFaceList(Input $input, Output $output)
    {
        // 指令输出
        $output->writeln('同步人脸信息');
        $lunaService = new LunaDrawService();
        $baseQuery = (new SwapTemplate())
            ->where('up_template_id', '>', 0)
            ->whereNull('face_list');
        $doneCnt = 0;
        $baseQuery->chunk(50, function ($list) use ($output, $lunaService, &$doneCnt) {
            foreach ($list as $item) {
                $job = "app\\job\\LunaSyncOfficialTemplateFaceList";
                \think\facade\Queue::later(1, $job, [
                    'id' => $item['id']
                ], 'freddy-debug');
            }
            $doneCnt += count($list);
            $output->writeln('in queue cnt: ' . $doneCnt);
        });
    }
}

