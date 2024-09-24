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

namespace app\adminapi\validate\draw;

use app\common\model\draw\DrawPromptExample;
use app\common\validate\BaseValidate;

/**
 * 绘画关键词示例
 * Class DrawPromptExampleValidate
 * @package app\adminapi\validate\draw
 */
class DrawPromptExampleValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|checkRecords',
        'prompt' => 'require',
        'prompt_en' => 'require',
        'sort' => 'egt:0',
        'status' => 'require',
    ];

    protected $message = [
        'id.require' => '参数缺失',
        'prompt.require' => '请填写关键词',
        'prompt_en.require' => '请填写英文关键词',
        'status.require' => '请选择状态',
        'sort.egt' => '排序值需大于或等于0',
    ];

    public function sceneAdd()
    {
        return $this->only(['prompt', 'prompt_en', 'sort', 'status']);
    }

    public function sceneEdit()
    {
        return $this->only(['id', 'prompt', 'prompt_en', 'sort', 'status']);
    }

    public function sceneStatus()
    {
        return $this->only(['id']);
    }

    public function sceneId()
    {
        return $this->only(['id']);
    }

    /**
     * @notes 校验记录
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author 段誉
     * @date 2023/6/28 9:47
     */
    protected function checkRecords($value, $rule, $data)
    {
        $record = DrawPromptExample::findOrEmpty($value);
        if ($record->isEmpty()) {
            return '记录信息不存在';
        }
        return true;
    }
}