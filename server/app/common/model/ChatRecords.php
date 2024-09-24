<?php
// +----------------------------------------------------------------------
// | likeshop开源商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop系列产品在gitee、github等公开渠道开源版本可免费商用，未经许可不能去除前后端官方版权标识
// |  likeshop系列产品收费版本务必购买商业授权，购买去版权授权后，方可去除前后端官方版权标识
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | likeshop团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshop.cn.team
// +----------------------------------------------------------------------

namespace app\common\model;


use app\common\enum\ChatEnum;
use app\common\model\BaseModel;
use app\common\model\creation\CreationCategory;
use app\common\model\creation\CreationModel;
use app\common\model\skill\Skill;
use app\common\model\skill\SkillCategory;
use app\common\service\FileService;
use think\model\concern\SoftDelete;

class ChatRecords extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';


    /**
     * @notes 创作类别/使用技能
     * @param $value
     * @param $data
     * @return string
     * @author ljj
     * @date 2023/4/25 10:39 上午
     */
    public function getOtherDescAttr($value,$data)
    {
        $result = '';
        switch ($data['type'])
        {
            case 1:
                break;
            case 2:
                $creation_model = CreationModel::where('id',$data['other_id'])->findOrEmpty()->toArray();
                if (empty($creation_model)) {
                    break;
                }
                $creation_category = CreationCategory::where('id',$creation_model['category_id'])->findOrEmpty()->toArray();
                $result = ($creation_category['name'] ?? '').'/'.$creation_model['name'];
                break;
            case 3:
                $skill = Skill::where('id',$data['other_id'])->findOrEmpty()->toArray();
                if (empty($skill)) {
                    break;
                }
                $skill_category = SkillCategory::where('id',$skill['category_id'])->findOrEmpty()->toArray();
                $result = ($skill_category['name'] ?? '').'/'.$skill['name'];
                break;
        }

        return $result;
    }

    /**
     * @notes 用户头像
     * @param $value
     * @param $data
     * @return string
     * @author ljj
     * @date 2023/4/25 10:43 上午
     */
    public function getAvatarAttr($value,$data)
    {
        return empty($value) ? '' : FileService::getFileUrl($value);
    }


    /**
     * @notes 提问获取器
     * @param $value
     * @param $data
     * @return mixed
     * @author ljj
     * @date 2023/6/19 7:43 下午
     */
    public function getAskAttr($value,$data)
    {
        $data = json_decode($value,true);
        if ($data) {
            return $data;
        }
        return $value;
    }

    /**
     * @notes 回复获取器
     * @param $value
     * @param $data
     * @return mixed
     * @author ljj
     * @date 2023/6/19 7:43 下午
     */
    public function getReplyAttr($value,$data)
    {
        $result = json_decode($value,true);
        if (!$result) {
            $result = $value;
        }
        if (!is_array($result)) {
            $result = [$result];
        }
        return $result;
    }

    /**
     * @notes 回复修改器
     * @param $value
     * @param $data
     * @return mixed
     * @author ljj
     * @date 2023/6/19 7:44 下午
     */
    public function setReplyAttr($value,$data)
    {
        if (is_array($value)) {
            return json_encode($value,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        } else {
            return $value;
        }
    }

    /**
     * @notes 审核状态
     * @param $value
     * @param $data
     * @return string
     * @author ljj
     * @date 2023/6/21 10:57 上午
     */
    public function getCensorStatusDescAttr($value,$data)
    {
        return ChatEnum::getCensorStatusDesc($data['censor_status']);
    }
}