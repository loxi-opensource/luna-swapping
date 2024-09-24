<?php

namespace app\common\service\aliyun;

use AlibabaCloud\SDK\Green\V20220302\Green;
use AlibabaCloud\SDK\Green\V20220302\Models\ImageModerationRequest;
use AlibabaCloud\Tea\Utils\Utils;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use app\adminapi\logic\setting\ImageCheckSettingLogic;
use app\common\utils\LogUtils;
use Darabonba\OpenApi\Models\Config;
use think\Exception;

/**
 * 图片审核增强版API
 * 文档地址：https://help.aliyun.com/document_detail/467829.html
 */
class ImageGreenCheckService
{
    private $client;

    private $systemConfig;

    public function __construct($systemConfig = null)
    {
        if (empty($systemConfig)) {
            $systemConfig = (new ImageCheckSettingLogic())->getConfig();
        }
        $this->systemConfig = $systemConfig;
        $configData = [
            "accessKeyId" => $systemConfig['access_key'],
            "accessKeySecret" => $systemConfig['secret_key'],
            "endpoint" => $systemConfig['endpoint'],
            "regionId" => $systemConfig['region_id'],
        ];

        $config = new Config($configData);
        $this->client = new Green($config);
    }

    public function checkImage($url)
    {
        if (!$this->systemConfig['audit_open']) {
//            LogUtils::log("图片检测服务-未开启直接放行", compact('url'));
            return true;
        }

        $request = new ImageModerationRequest();
        $request->service = "baselineCheck";
        $serviceParameters = array(
            'imageUrl' => $url,
            'dataId' => md5($url)
        );
        $request->serviceParameters = json_encode($serviceParameters);

        // 创建RuntimeObject实例并设置运行参数。
        $runtime = new RuntimeOptions([]);
        $runtime->readTimeout = 10000;
        $runtime->connectTimeout = 10000;

        $response = $this->client->imageModerationWithOptions($request, $runtime);
        if (Utils::equalNumber(500, $response->statusCode) || !Utils::equalNumber(200, $response->body->code)) {
            $e = new Exception("Image detection service is abnormal");
            LogUtils::record($e, "图片安全检测服务异常", compact('response', 'request'));
            throw $e;
        }

        // {"Code":200,"Data":{"DataId":"64b567562dd31","Result":[{"Label":"nonLabel"}]},"Msg":"success","RequestId":"D281FB6A-0E6C-578A-B1B0-73E1BD9C9282"}
        $resp = $response->body->toMap();
//        LogUtils::log("freddy-debug", compact('resp', 'request'));

        if (isset($resp['Data']['Result'][0]['Label']) && $resp['Data']['Result'][0]['Label'] == 'nonLabel') {
            return true;
        };
        if (count($resp['Data']['Result'])) {
            $riskLabels = array_filter($resp['Data']['Result'], function ($item) {
                return $item['Confidence'] > 85;
            });
            if (!count($riskLabels)) {
                LogUtils::log("图片检测服务-风险度不够已放行", compact('resp', 'request', 'riskLabels'));
                return true;
            }
            if (count($riskLabels)) {
                LogUtils::log("图片检测服务-风险度过高已拦截", compact('resp', 'request', 'riskLabels'));
                return false;
            }
        }

        LogUtils::log("图片检测服务-响应不太对直接放行", compact('resp', 'request'));
        return true;
    }
}
