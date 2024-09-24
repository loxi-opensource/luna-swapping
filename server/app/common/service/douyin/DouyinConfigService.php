<?php
namespace app\common\service\douyin;

use app\common\enum\PayEnum;
use app\common\enum\user\UserTerminalEnum;
use app\common\model\pay\PayConfig;
use app\common\service\ConfigService;

class DouyinConfigService
{
    public static function getMnpConfig()
    {
        return [
            'app_id' => ConfigService::get('douyin_mnp_setting', 'app_id'),
            'secret' => ConfigService::get('douyin_mnp_setting', 'app_secret'),
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => app()->getRootPath() . 'runtime/douyin/' . date('Ym') . '/' . date('d') . '.log'
            ],
        ];
    }

    /**
     * @notes 根据终端获取支付配置
     * @param $terminal
     * @return array
     * @author 段誉
     * @date 2023/2/27 15:45
     */
    public static function getPayConfigByTerminal($terminal)
    {
        switch ($terminal) {
            case UserTerminalEnum::DOUYIN_MMP:
                $notifyUrl = (string)url('pay/notifyMnpDouyin', [], false, true);
                break;
        }

        $pay = PayConfig::where(['pay_way' => PayEnum::DOUYIN_PAY])->findOrEmpty()->toArray();
        //判断是否已经存在证书文件夹，不存在则新建
        if (!file_exists(app()->getRootPath() . 'runtime/cert')) {
            mkdir(app()->getRootPath() . 'runtime/cert', 0775, true);
        }
        //写入文件
        $apiclientCert = $pay['config']['apiclient_cert'] ?? '';
        $apiclientKey = $pay['config']['apiclient_key'] ?? '';

        $certPath = app()->getRootPath() . 'runtime/cert/' . md5($apiclientCert) . '.pem';
        $keyPath = app()->getRootPath() . 'runtime/cert/' . md5($apiclientKey) . '.pem';

        if (!empty($apiclientCert) && !file_exists($certPath)) {
            static::setCert($certPath, trim($apiclientCert));
        }
        if (!empty($apiclientKey) && !file_exists($keyPath)) {
            static::setCert($keyPath, trim($apiclientKey));
        }

        return [
            // 商户号
            'mch_id' => $pay['config']['mch_id'] ?? '',
            // 商户证书
            'private_key' => $keyPath,
            'certificate' => $certPath,
            // v3 API 秘钥
            'secret_key' => $pay['config']['pay_sign_key'] ?? '',
            'notify_url' => $notifyUrl,
            'http' => [
                'throw'  => true, // 状态码非 200、300 时是否抛出异常，默认为开启
                'timeout' => 5.0,
            ]
        ];
    }


    /**
     * @notes 临时写入证书
     * @param $path
     * @param $cert
     * @author 段誉
     * @date 2023/2/27 15:48
     */
    public static function setCert($path, $cert)
    {
        $fopenPath = fopen($path, 'w');
        fwrite($fopenPath, $cert);
        fclose($fopenPath);
    }
}
