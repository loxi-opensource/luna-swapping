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
namespace app\common\model\creation;
use app\common\model\BaseModel;

/**
 * 创作模型类
 * Class CreationCategory
 * @package app\common\model\creation
 */
class CreationModel extends BaseModel
{

    protected $json = ['form'];

    // 设置JSON数据返回数组
    protected $jsonAssoc = true;


    public function category()
    {
        return $this->hasOne(CreationCategory::class,'id');
    }


    /**
     * @notes 词汇属性
     * @param $value
     * @param $data
     * @return float
     * @author ljj
     * @date 2023/6/8 2:32 下午
     */
    public function getTemperatureAttr($value,$data)
    {
        return floatval($value);
    }

    /**
     * @notes 随机属性
     * @param $value
     * @param $data
     * @return float
     * @author ljj
     * @date 2023/6/8 2:32 下午
     */
    public function getTopPAttr($value,$data)
    {
        return floatval($value);
    }

    /**
     * @notes 话题属性
     * @param $value
     * @param $data
     * @return float
     * @author ljj
     * @date 2023/6/8 2:32 下午
     */
    public function getPresencePenaltyAttr($value,$data)
    {
        return floatval($value);
    }

    /**
     * @notes 重复属性
     * @param $value
     * @param $data
     * @return float
     * @author ljj
     * @date 2023/6/8 2:32 下午
     */
    public function getFrequencyPenaltyAttr($value,$data)
    {
        return floatval($value);
    }
}