<?php

namespace app\common\service\douyin;

use app\common\enum\PayEnum;
use EasyWeChat\Kernel\Exceptions\Exception;
use GuzzleHttp\Client;
use think\facade\Cache;

class DouyinMnpService
{
    protected $config;
    protected $client;

    private $accessTokenCacheKey = "douyin_mnp_token";

    const DefaultOrderImgUrl = 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/uploads/images/20230813/3151454b-d799-47b2-83ba-c674a5794a99.png';
    const MnpOrderPageUrl = '/pages/user/index';

    public function __construct()
    {
        $this->config = $this->getConfig();
        $this->client = new Client([
            'base_uri' => 'https://developer.toutiao.com',
            'timeout' => 10,
        ]);
    }

    protected function getConfig()
    {
        $config = DouyinConfigService::getMnpConfig();
        if (empty($config['app_id']) || empty($config['secret'])) {
            throw new \Exception('请先设置小程序配置');
        }
        return $config;
    }

    public function getMnpResByCode(string $code, string $anonymousCode)
    {

        $params = [
            "appid" => $this->config['app_id'],
            "secret" => $this->config['secret'],
            "anonymousCode" => $anonymousCode,
            "code" => $code
        ];
        $resp = $this->client->post('/api/apps/v2/jscode2session', [
            'json' => $params,
        ]);
        $respData = json_decode($resp->getBody(), true);
        if (empty($respData)) {
            throw new Exception("getMnpResByCode fail: empty resp");
        }

        // "session_key": "CxyWN3pDPKahOlMR89Qs8Q==",
        //		"openid": "_000q7gWIPXKlOifTNLbhpuaSacP9qnTnvjm",
        //		"anonymous_openid": "",
        //		"unionid": "b8c41df4-83fe-4024-b3e1-029dd2924044",
        //		"dopenid": ""
        if (!isset($respData['data']['openid']) || empty($respData['data']['openid'])) {
            throw new Exception('获取openID失败');
        }

        return $respData['data'];
    }

    public function getAccessToken($forceRefresh = false)
    {
        if (!$forceRefresh) {
            $cachedToken = Cache::get($this->accessTokenCacheKey);
            if ($cachedToken) {
                return $cachedToken;
            }
        }

        $params = [
            "appid" => $this->config['app_id'],
            "secret" => $this->config['secret'],
            'grant_type' => 'client_credential'
        ];
        $resp = $this->client->post('/api/apps/v2/token', [
            'json' => $params,
        ]);
        $respData = json_decode($resp->getBody(), true);
        if (empty($respData)) {
            throw new Exception("douyinMnp getAccessToken fail: empty resp");
        }
        if (!isset($respData['data']['access_token'])) {
            throw new Exception("getAccessToken fail");
        }
        $newToken = $respData['data']['access_token'];
        Cache::set($this->accessTokenCacheKey, $newToken, $respData['data']['expires_in'] - 60);
        return $newToken;
    }

    public function sendTemplateMsg($templateID, $openID, $data, $page = null)
    {
        //{
        //  "access_token": "b13b4c7679150245dac2249aafd8aca8e9dceaff9e22cee05e1d30fb67e18916358d73c235fcaab9007ec0976ee40d26ee56a43c32398b9d6680105e5535cd1ef40a803d790668581************",
        //  "app_id": "31198cf00b********",
        //  "tpl_id": "MSG38489d04608c5f0fdeb565fc5114afff6410*******",
        //  "open_id": "36d4bd3c8****",
        //  "data": {
        //    "物品名称": "测试值0",
        //    "购买金额": "测试值1"
        //  },
        //  "page": "pages/index?a=b"
        //}
        $params = [
            "app_id" => $this->config['app_id'],
            'access_token' => $this->getAccessToken(),
            'tpl_id' => $templateID,
            'open_id' => $openID,
            'data' => $data,
//            'page' => $page
        ];
//        header('content-type:application/json');
//        exit(json_encode([$params]));
        $resp = $this->client->post('/api/apps/subscribe_notification/developer/v1/notify', [
            'json' => $params,
        ]);
        $respData = json_decode($resp->getBody(), true);
        if (empty($respData)) {
            throw new Exception("douyinMnp sendTemplateMsg fail: empty resp");
        }
        return $respData;
    }

    public function orderSync($openID, $order)
    {
        $params = [
            'access_token' => $this->getAccessToken(),
            'app_name' => 'douyin',
            'open_id' => $openID,
            'order_status' => self::getOrderStatus($order),
            'order_type' => 0,
            'update_time' => intval(microtime(true) * 1000)
        ];

        $orderDetail = [
            "order_id" => $order['sn'],
            "create_time" => intval(strtotime($order['create_time']) . "000"),
            'status' => self::getOrderStatusText($order),
            'amount' => 1,
            "total_price" => intval(strval($order['order_amount'] * 100)),
            'detail_url' => self::MnpOrderPageUrl,
            'item_list' => [
                [
                    'item_code' => "recharge_pkgid_" . $order['recharge_package_id'],
                    'img' => self::DefaultOrderImgUrl,
                    'title' => $order['recharge_package_info']['describe'],
                    'amount' => 1,
                    'price' => intval(strval($order['order_amount'] * 100)),
                ]
            ]
        ];

        $params['order_detail'] = json_encode($orderDetail, JSON_UNESCAPED_UNICODE);
        $json = json_encode($params, JSON_UNESCAPED_UNICODE);
//        file_put_contents('./res.txt', $json);
//        exit();

        try {
            $resp = $this->client->post('/api/apps/order/v2/push', [
                'json' => $params,
            ]);
            $respData = json_decode($resp->getBody(), true);
            if (empty($respData)) {
                throw new Exception("douyin sync-order fail: empty resp");
            }
            if (isset($respData['err_code']) && $respData['err_code'] === 0) {
                return true;
            } else {
                throw new Exception(json_encode(compact('respData', 'params')));
            }
        } catch (\Exception $e) {
            $record = [
                __CLASS__,
                __FUNCTION__,
                $e->getFile(),
                $e->getLine(),
                $e->getMessage()
            ];
            \think\facade\Log::record('抖音支付订单同步异常: ' . implode('-', $record));
            return false;
        }
    }

    private static function getOrderStatus($order)
    {
        // 普通小程序订单订单状态，POI 订单可以忽略
        //0：待支付
        //1：已支付
        //2：已取消（用户主动取消或者超时未支付导致的关单）
        //4：已核销（核销状态是整单核销,即一笔订单买了 3 个券，核销是指 3 个券核销的整单）
        //5：退款中
        //6：已退款
        //8：退款失败
        //注意：普通小程序订单必传，担保支付分账依赖该状态

        switch ($order['pay_status']) {
            case PayEnum::UNPAID:
                return 0;
            case PayEnum::ISPAID:
                return 1;
            default:
                // todo 补充更多
                return "";
        }
    }

    private static function getOrderStatusText($order)
    {
        //订单状态，建议采用以下枚举值：
        //待支付
        //已支付
        //已取消
        //已超时
        //已核销
        //退款中
        //已退款
        //退款失败
        switch ($order['pay_status']) {
            case PayEnum::UNPAID:
                return "待支付";
            case PayEnum::ISPAID:
                return "已支付";
            default:
                // todo 补充更多
                return "";
        }
    }
}
