<?php

namespace app\job;

use app\common\model\swap_template\SwapTemplate;
use app\common\service\luna\LunaDrawService;
use app\common\utils\LogUtils;
use think\helper\Arr;
use think\queue\Job;

class LunaSyncOfficialTemplateFaceList
{
    public function fire(Job $job, $data)
    {
        echo "recv msg: ", json_encode($data), PHP_EOL;

        $item = SwapTemplate::find($data['id']);
        if ($item->face_list) {
            echo 'face list already exists ', $item['up_template_id'], PHP_EOL;
            $job->delete();
            return;
        }
        $lunaService = new LunaDrawService();
        $res = $lunaService->getMaterialFileFaceList($item['up_template_id']);
        if (!$res) {
            LogUtils::log('luna getMaterialFileFaceList failed', ['up_template_id' => $item['up_template_id']]);
            echo 'failed ', $item['up_template_id'], PHP_EOL;
            $job->delete();
            return;
        }

        $faceList = Arr::get($res, 'fileFaceList', []);
        foreach ($faceList as &$face) {
            $face['x'] = $face['boundingBoxLeft'];
            $face['y'] = $face['boundingBoxTop'];
            $face['w'] = $face['boundingBoxWidth'];
            $face['h'] = $face['boundingBoxHeight'];
        }
        $item->face_list = $faceList;
        $item->save();
        echo 'update face list success ', $item['up_template_id'], PHP_EOL;
        $job->delete();
        return;
    }

    public function failed($data, \Exception $e)
    {
        echo 'failed job ', json_encode($data) . " " . $e->getMessage(), PHP_EOL;
    }
}
