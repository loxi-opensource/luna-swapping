<?php
declare (strict_types=1);

namespace app\command;

use app\common\enum\CacheTagEnum;
use app\common\enum\LunaDrawConst;
use app\common\model\LunaTemplate;
use app\common\model\LunaTemplateGroup;
use app\common\service\luna\LunaDrawService;
use app\common\utils\CacheUtils;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class LunaUpgradeOfficialTemplate extends Command
{
    /**
     * @var LunaDrawService
     */
    private $lunaService;

    protected function configure()
    {
        // 指令配置
        $this->setName('luna_upgrade_official_template')
            ->setDescription('更新LunaAI写真官方模板');
    }

    protected function initialize(Input $input, Output $output)
    {
        $this->lunaService = new LunaDrawService();
    }

    protected function execute(Input $input, Output $output)
    {
        // 同步1对多写真分组
        $this->syncOne2ManyTemplateGroup(LunaDrawConst::TemplateGroupCategoryAvatar, 'avatar');
        // 同步1v1分组
        $this->syncOne2OneTemplateGroup(LunaDrawConst::TemplateGroupCategory1v1, '1v1');
        // 同步1对多电影定妆照分组
        $this->syncOne2ManyTemplateGroup(LunaDrawConst::TemplateGroupCategoryFilm, 'film');
        // 同步全部分组模板图
        $this->syncTemplates();
    }

    function syncOne2ManyTemplateGroup($categoryId, $categoryName)
    {
        // 同步1对多写真分组
        $cacheKey = 'luna_draw_tags:' . $categoryId;
        $res = CacheUtils::remember($cacheKey, function () use ($categoryId) {
            $res = $this->lunaService->getTagList($categoryId);
            $res['tagList'] = array_values(array_filter($res['tagList'], function ($item) {
                return !is_null($item['id']);
            }));
            return $res;
        }, 300, CacheTagEnum::LUNA_DRAW);
        foreach ($res['tagList'] as $group) {
            if (empty($group['id'])) {
                continue;
            }
            if (LunaTemplateGroup::where('up_group_id', $group['id'])->find()) {
                continue;
            }
            LunaTemplateGroup::create([
                'name' => $group['tagName'],
                'up_group_id' => $group['id'],
                'up_group_name' => $group['tagName'],
                'category' => $categoryName,
            ]);
        }
    }

    function syncOne2OneTemplateGroup($categoryId, $categoryName)
    {
        $cacheKey = 'luna_draw_multiTagListWithPreview:' . $categoryId;
        $res = CacheUtils::remember($cacheKey, function () use ($categoryId) {
            return $this->lunaService->getTagList($categoryId, true);
        }, 300, CacheTagEnum::LUNA_DRAW);
        foreach ($res['tagList'] as $group) {
            if (empty($group['id'])) {
                continue;
            }
            if (LunaTemplateGroup::where('up_group_id', $group['id'])->find()) {
                continue;
            }
            LunaTemplateGroup::create([
                'name' => $group['tagName'],
                'up_group_id' => $group['id'],
                'up_group_name' => $group['tagName'],
                'category' => $categoryName,
            ]);
        }
    }

    function syncTemplates()
    {
        $rows = LunaTemplateGroup::where('up_group_id', '>', 0)->select();
        foreach ($rows as $row) {
            $upGroupId = $row['up_group_id'];
            $cacheKey = 'luna_draw_tag_files:' . $upGroupId;
            $res = CacheUtils::remember($cacheKey, function () use ($upGroupId) {
                return $this->lunaService->getMaterialFilesByTagID($upGroupId);
            }, 300, CacheTagEnum::LUNA_DRAW);

            foreach ($res['materialFiles'] as $template) {
                $templateExist = LunaTemplate::where('up_template_id', $template['id'])->find();
                if ($templateExist) {
//                    LogUtils::log('template exist, should update imageUrl, id='. $template['id']);
                    $templateExist->save([
                        'image_url' => $template['publicUrl'],
                    ]);
                    continue;
                }
                LunaTemplate::create([
                    'group_id' => $row['id'],
                    'up_template_id' => $template['id'],
                    'image_url' => $template['publicUrl'],
                    'name' => $res['tagName'] . '_' . $template['fileName'],
                ]);
            }
        }
    }

}

