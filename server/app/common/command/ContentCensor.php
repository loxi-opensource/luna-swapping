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

namespace app\common\command;

use app\common\enum\ChatEnum;
use app\common\model\ChatRecords;
use app\common\service\ConfigService;
use think\console\Command;
use think\console\Input;
use think\console\Output;


class ContentCensor extends Command
{
    protected function configure()
    {
        $this->setName('content_censor')
            ->setDescription('内容审核');
    }


    protected function execute(Input $input, Output $output)
    {
        //百度内容审核配置
        $is_open = ConfigService::get('content_censor','is_open',0);
        if ($is_open != 1) {
            return true;
        }
        $APP_ID = ConfigService::get('content_censor','app_id');
        $API_KEY = ConfigService::get('content_censor','api_key');
        $SECRET_KEY = ConfigService::get('content_censor','secret_key');
        if (!$APP_ID || !$API_KEY || !$SECRET_KEY) {
            throw new \think\Exception('内容审核配置缺失', 10006);
        }
        $client = new \aip\AipContentCensor($APP_ID, $API_KEY, $SECRET_KEY);

        $lists = ChatRecords::field('id,ask,reply')->where(['censor_status'=>ChatEnum::CENSOR_STATUS_WAIT])->limit(200)->select()->toArray();
        $update_data = [];
        foreach ($lists as $list) {
            //拼接提问字符串
            $ask = '';
            if (is_array($list['ask'])) {
                foreach ($list['ask'] as $ask_val) {
                    if (is_array($ask_val)) {
                        $ask .= implode($ask_val);
                    } else {
                        $ask .= $ask_val;
                    }
                }
            } else {
                $ask = $list['ask'];
            }
            //拼接回复字符串
            $reply = '';
            if (is_array($list['reply'])) {
                foreach ($list['reply'] as $reply_val) {
                    if (is_array($reply_val)) {
                        $reply .= implode($reply_val);
                    } else {
                        $reply .= $reply_val;
                    }
                }
            } else {
                $reply = $list['ask'];
            }

            //审核字符串
            $str = splitString($ask.$reply,6000);
            $result_data = [];
            $censor_status = ChatEnum::CENSOR_STATUS_WAIT;
            foreach ($str as $str_val) {
                $result = $client->textCensorUserDefined($str_val);
                $result_data[] = $result;
                if (isset($result['error_code'])) {
                    break;
                }
                if (isset($result['conclusionType']) && !in_array($censor_status,[ChatEnum::CENSOR_STATUS_NON_COMPLIANCE,ChatEnum::CENSOR_STATUS_SUSPECTED,ChatEnum::CENSOR_STATUS_FAIL])) {
                    $censor_status = $result['conclusionType'];
                }
            }
            if (!empty($result_data)) {
                $update_data[] = [
                    'id' => $list['id'],
                    'censor_status' => $censor_status,
                    'censor_result' => json_encode($result_data),
                ];
            }
        }

        if (!empty($update_data)) {
            (new ChatRecords())->saveAll($update_data);
        }

        return true;
    }
}