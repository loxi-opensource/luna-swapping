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

use app\common\model\draw\DrawPrompt;
use app\common\validate\BaseValidate;

/**
 * 绘画关键词
 * Class DrawPromptValidate
 * @package app\adminapi\validate\draw
 */
class DrawPromptValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|checkRecords',
        'prompt' => 'require',
        'prompt_en' => 'require',
        'category_id' => 'require',
        'status' => 'require',
    ];

    protected $message = [
        'id.require' => '参数缺失',
        'prompt.require' => '请填写关键词',
        'prompt_en.require' => '请填写英文关键词',
        'category_id.require' => '请选择分类',
        'status.require' => '请选择状态'
    ];

    public function sceneAdd()
    {
        return $this->only(['prompt', 'category_id', 'status'])
            ->append('prompt', 'checkPrompt');
    }

    public function sceneEdit()
    {
        return $this->only(['id', 'prompt', 'prompt_en', 'category_id', 'status']);
    }

    public function sceneStatus()
    {
        return $this->only(['id', 'status']);
    }

    public function sceneId()
    {
        return $this->only(['id']);
    }

    /**
     * @notes 校验关键词
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author 段誉
     * @date 2023/6/27 15:27
     */
    protected function checkPrompt($value, $rule, $data)
    {
        if (!is_array($value)) {
            return '关键词格式错误';
        }

        foreach ($value as $item) {
            if (mb_strlen($item) < 3) {
                return "关键词" . $item . "异常";
            }

            $index = strpos($item, '&');
            if ($index !== false) {
                if ($index == 0 || $index == mb_strlen($item) - 1) {
                    return "关键词" . $item . "异常";
                }
            } else {
                return "关键词" . $item . "缺少分隔符";
            }
        }
        return true;
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
        $record = DrawPrompt::findOrEmpty($value);
        if ($record->isEmpty()) {
            return '记录信息不存在';
        }
        return true;
    }
}