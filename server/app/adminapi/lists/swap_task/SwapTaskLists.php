<?php

namespace app\adminapi\lists\swap_task;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\lists\ListsSearchInterface;
use app\common\model\luna\SwapTask;
use app\common\service\luna\LunaDrawService;
use EasyWeChat\Kernel\Support\Arr;

class SwapTaskLists extends BaseAdminDataLists implements ListsSearchInterface
{
    public function setSearch(): array
    {
        return [
            '=' => ['user_id', 'up_task_id', 'status',],
        ];
    }

    public function lists(): array
    {
        $rows = SwapTask::where($this->searchWhere)
            ->with(['strategy'])
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['id' => 'desc'])
            ->select()
            ->toArray();
        if (empty($rows)) {
            return $rows;
        }

        $rows = array_map(function ($r) {
            if ($r['result_images']) {
                $r['result_images'] = array_map(function ($img) {
                    return LunaDrawService::LUNA_OSS_BASE_URL . '/' . $img;
                }, $r['result_images']);
            }
            $templateLabelList = [];
            $templateLabelList = collect(Arr::get($r, 'user_draft.templates'))->map(function ($t) {
                $label = Arr::get($t, 'id') . '-' . Arr::get($t, 'group_name') . '/' . Arr::get($t, 'name');
                $maxLen = 30;
                if (mb_strlen($label) <= $maxLen) {
                    return $label;
                }
                return mb_substr($label, 0, $maxLen) . '...';
            });

            $r['template_label_list'] = $templateLabelList;
            $r['is_collection'] = Arr::get($r, 'user_draft.is_collection') ? '是' : '否';
            return $r;
        }, $rows);

        return $rows;
    }

    public function count(): int
    {
        return SwapTask::where($this->searchWhere)->count();
    }

}
