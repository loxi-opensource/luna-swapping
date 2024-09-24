<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------

namespace app\adminapi\lists;


use app\adminapi\lists\BaseAdminDataLists;
use app\common\model\file\File;
use app\common\model\LunaDrawingTask;
use app\common\lists\ListsSearchInterface;
use app\common\service\FileService;
use think\helper\Arr;


/**
 * LunaDrawingTask列表
 * Class LunaDrawingTaskLists
 * @package app\adminapi\lists
 */
class LunaDrawingTaskLists extends BaseAdminDataLists implements ListsSearchInterface
{


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author likeadmin
     * @date 2023/11/07 10:49
     */
    public function setSearch(): array
    {
        return [
            '=' => ['user_id', 'order_sn', 'order_id', 'up_task_id', 'tag_file_id', 'status', 'is_retry'],
        ];
    }


    /**
     * @notes 获取列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author likeadmin
     * @date 2023/11/07 10:49
     */
    public function lists(): array
    {
        $rows = LunaDrawingTask::where($this->searchWhere)
            ->field(['id', 'user_id', 'order_sn', 'order_id', 'up_task_id', 'draw_number', 'tag_file_id', 'user_file_id', 'up_user_file_id', 'result_images', 'result_images_thumb', 'status', 'is_retry', 'error_msg', 'create_time', 'is_watermark'])
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['id' => 'desc'])
            ->select()
            ->toArray();
        if (empty($rows)) {
            return $rows;
        }
        $fileIdList = [];
        $rows = array_map(function ($r) use (&$fileIdList) {
            $r['result_images'] = json_decode($r['result_images']);
            $r['result_images_thumb'] = json_decode($r['result_images_thumb']);
            $fileIdList = array_merge($fileIdList, explode(',', $r['user_file_id']));
            return $r;
        }, $rows);

        $fileIdList = array_values(array_unique(array_filter($fileIdList)));
        $fileIdUrlMap = File::whereIn('id', $fileIdList)->column('uri', 'id');

        $rows = array_map(function ($r) use ($fileIdUrlMap) {
            $r['user_file_list'] = [];
            if ($r['user_file_id']) {
                $fileIds = explode(',', $r['user_file_id']);
                foreach ($fileIds as $id) {
                    if (!isset($fileIdUrlMap[$id])) {
                        continue;
                    }
                    $r['user_file_list'][] = FileService::getFileUrl($fileIdUrlMap[$id]);
                }
            }
            return $r;
        }, $rows);

        return $rows;
    }


    /**
     * @notes 获取数量
     * @return int
     * @author likeadmin
     * @date 2023/11/07 10:49
     */
    public function count(): int
    {
        return LunaDrawingTask::where($this->searchWhere)->count();
    }

}
