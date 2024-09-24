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
namespace app\common\service;
use app\common\logic\BaseLogic;
use app\common\model\SensitiveWord;
use DfaFilter\SensitiveHelper;

/**
 * ai统一逻辑服务类
 * Class AiChatService
 * @package app\common\service
 */
class AiChatService extends BaseLogic
{

    /**
     * @notes 审核全局指令
     * @param $messages
     * @author cjhao
     * @date 2023/6/21 15:14
     */
    public static function getGlobalDirectives(&$message,$type = 'openai')
    {

        //全局审核指令
        $globalDirectives = ConfigService::get('chat_config','global_directives','');
        if(!$globalDirectives){
            return ;
        }
        switch ($type){
            case 'openai':
                $message[] = ['role' => 'system', 'content' => $globalDirectives];
                break;

        }

    }

    /**
     * @notes 敏感词验证
     * @param string $content
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     * @throws \DfaFilter\Exceptions\PdsSystemException
     * @throws \think\Exception
     * @author cjhao
     * @date 2023/6/21 10:42
     */
    public static function Sensitive(string $content):void
    {
        //敏感词过滤
        $isSensitive      = ConfigService::get('chat_config','is_sensitive',1); //默认开启
        if(!$isSensitive){
            return ;
        }
        //获取数据库敏感词
        $sensitiveWord = SensitiveWord::where(['status'=>1])->column('word');
        //一条数据可能含有多个敏感词，'；'分隔开
        $sensitive_arr = [];
        foreach ($sensitiveWord as $sensitiveWordValue) {
            $sensitive_arr = array_merge($sensitive_arr,explode('；',$sensitiveWordValue));
        }

        //读取敏感词文件
        //读取加密的密钥
        $file = fopen("../extend/sensitive_key.bin", "rb");
        $key = fread($file, 32);
        $iv = fread($file, 16);
        fclose($file);
        //读取加密的数据
        $ciphertext = file_get_contents("../extend/sensitive_data.bin");
        //使用 CBC 模式解密数据
        $plaintext = openssl_decrypt($ciphertext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

        //过滤敏感词
        $wordFilePath = explode(PHP_EOL,trim($plaintext));
        $sensitiveWord = array_merge($wordFilePath,$sensitive_arr);
        $handle = SensitiveHelper::init()->setTree($sensitiveWord);
        $sensitiveWordGroup = $handle->getBadWord($content);// 获取内容中所有的敏感词
        if (!empty($sensitiveWordGroup)) {
            throw new \think\Exception('提问存在敏感词：'.implode(',',$sensitiveWordGroup));
        }

    }






    /**
     * @notes 返回流数据
     * @param string $event
     * @param string $data
     * @param string $id
     * @param int $index
     * @author cjhao
     * @date 2023/6/21 18:21
     */
    public static function parseReturnStream(string $event,string $data,string $id,string $type,int $index = 0)
    {
        $chatMessage = [
            'event' => $event,
            'id'    => $id,
            'data'  => $data,
            'index' => $index,
            'type'  =>$type,
        ];

        $jsonchatMessage = json_encode($chatMessage);
        echo "data:".$jsonchatMessage."\n\n";
        ob_flush();
        flush();

    }


}