<?php

namespace app\common\service\pay;

use app\api\logic\UserLogic;
use app\common\enum\PayEnum;
use app\common\logic\PayNotifyLogic;
use app\common\model\dict\DictData;
use app\common\model\member\MemberOrder;
use app\common\model\recharge\RechargeOrder;
use app\common\service\douyin\DouyinMnpService;
use GuzzleHttp\Client;
use think\facade\Log;

class DouyinPayService extends BasePayService
{
    // todo 后台配置
    const APPID = 'todo';
    const SALT = 'todo';
    const TOKEN = 'todo';

    protected $notifyUrl;
    protected $host;

    public function __construct()
    {
        $notifyUrl = (string)url('pay/notifyMnpDy', [], false, true);
        $this->notifyUrl = $notifyUrl;
        $this->host = 'https://developer.toutiao.com';
    }

    public function pay($from, $order)
    {
//        header('content-type:application/json');
//        exit(json_encode([$order]));
        try {
            $client = new Client([
                'base_uri' => $this->host,
                'timeout' => 10,
            ]);

            $params = [
                "app_id" => self::APPID,
                "out_order_no" => $order['sn'],
                "total_amount" => intval(strval($order['order_amount'] * 100)),
                'subject' => 'AI写真',
                'body' => 'AI写真',
                'valid_time' => 900,
                'notify_url' => $this->notifyUrl,
                'cp_extra' => $from,
            ];
            $params['sign'] = $this->sign($params);
            $resp = $client->post('/api/apps/ecpay/v1/create_order', [
                "json" => $params
            ]);
            $respData = json_decode($resp->getBody(), true);
//            header('content-type:application/json');
//            exit(json_encode([compact('params', 'respData')]));

            Log::record("douyin-pay/debug: " . json_encode(compact('order', 'params', 'respData'), JSON_UNESCAPED_UNICODE));

            if (empty($respData)) {
                throw new Exception("call douyin-pay/create_order fail: empty resp");
            }

            return [
                'config' => $respData['data'] ?? [],
                'pay_way' => PayEnum::DOUYIN_PAY
            ];
        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    public function notify($params)
    {
        try {
            if (!$this->verifyNotifySign($params)) {
                throw new \Exception('验签失败');
            }
            if ($params['type'] != 'payment') {
                return false;
            }

            $data = json_decode($params['msg'], true);
            if ($data['status'] != 'SUCCESS') {
                return false;
            }

            $extra['transaction_id'] = $data['channel_no'] ?? ($data['payment_order_no'] ?? "");
            //验证订单是否已支付
            switch ($data['cp_extra']) {
                case 'recharge':
                    $order = RechargeOrder::where(['sn' => $data['cp_orderno']])->findOrEmpty();
                    if ($order->isEmpty() || $order->pay_status == PayEnum::ISPAID) {
                        return true;
                    }
                    PayNotifyLogic::handle('recharge', $data['cp_orderno'], $extra);
                    break;
                case 'member':
                    $order = MemberOrder::where(['sn' => $data['cp_orderno']])->findOrEmpty();
                    if ($order->isEmpty() || $order->pay_status == PayEnum::ISPAID) {
                        return true;
                    }
                    PayNotifyLogic::handle('member', $data['cp_orderno'], $extra);
                    break;
            }

            return true;
        } catch (\Exception $e) {
            $record = [
                __CLASS__,
                __FUNCTION__,
                $e->getFile(),
                $e->getLine(),
                $e->getMessage()
            ];
            Log::write(implode('-', $record));
            $this->setError($e->getMessage());
            return false;
        }
    }

    // 同步订单信息
    public function syncOrder($orderWithUserInfo)
    {
        if (!isset($orderWithUserInfo['user'])) {
            throw new \InvalidArgumentException('需要提供关联的用户信息');
        }
        $openid = UserLogic::getOpenID($orderWithUserInfo['user']);
        return (new DouyinMnpService())->orderSync($openid, $orderWithUserInfo);
    }

    function verifyNotifySign($data)
    {
        $originStrArr = [self::TOKEN, $data['timestamp'], $data['nonce'], $data['msg']];
        sort($originStrArr, SORT_STRING);
        $originStr = implode('', $originStrArr);
        $mySign = sha1($originStr);
        Log::record("douyin-pay-notify verifyNotifySign: orginStr: " . $originStr . ", mysign: " . $mySign . ", hersign: " . $data['msg_signature']);
        return $mySign == $data['msg_signature'];
    }

    function sign($map)
    {
        $rList = [];
        foreach ($map as $k => $v) {
            if ($k == "other_settle_params" || $k == "app_id" || $k == "sign" || $k == "thirdparty_id")
                continue;

            $value = trim(strval($v));
            if (is_array($v)) {
                $value = $this->arrayToStr($v);
            }

            $len = strlen($value);
            if ($len > 1 && substr($value, 0, 1) == "\"" && substr($value, $len - 1) == "\"")
                $value = substr($value, 1, $len - 1);
            $value = trim($value);
            if ($value == "" || $value == "null")
                continue;
            $rList[] = $value;
        }
        $rList[] = self::SALT;
        sort($rList, SORT_STRING);
        return md5(implode('&', $rList));
    }

    function arrayToStr($map)
    {
        $isMap = $this->isArrMap($map);

        $result = "";
        if ($isMap) {
            $result = "map[";
        }

        $keyArr = array_keys($map);
        if ($isMap) {
            sort($keyArr);
        }

        $paramsArr = array();
        foreach ($keyArr as $k) {
            $v = $map[$k];
            if ($isMap) {
                if (is_array($v)) {
                    $paramsArr[] = sprintf("%s:%s", $k, $this->arrayToStr($v));
                } else {
                    $paramsArr[] = sprintf("%s:%s", $k, trim(strval($v)));
                }
            } else {
                if (is_array($v)) {
                    $paramsArr[] = $this->arrayToStr($v);
                } else {
                    $paramsArr[] = trim(strval($v));
                }
            }
        }

        $result = sprintf("%s%s", $result, join(" ", $paramsArr));
        if (!$isMap) {
            $result = sprintf("[%s]", $result);
        } else {
            $result = sprintf("%s]", $result);
        }

        return $result;
    }

    function isArrMap($map)
    {
        foreach ($map as $k => $v) {
            if (is_string($k)) {
                return true;
            }
        }
        return false;
    }
}

