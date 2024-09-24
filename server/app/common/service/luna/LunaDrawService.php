<?php

namespace app\common\service\luna;

use app\adminapi\logic\setting\LunaServiceSettingLogic;
use app\common\enum\BusinessErrorCodeEnum;
use app\common\enum\CacheTagEnum;
use app\common\exception\BaseException;
use app\common\types\luna\FaceMappingList;
use app\common\utils\CacheUtils;
use app\common\utils\LogUtils;
use GuzzleHttp\Client;
use think\Exception;
use think\facade\Cache;

class LunaDrawService
{
    private $host = 'https://prod.luna.aws.iartai.com';

    private $secret;
    private $secretKey;

    private $accessToken = null;
    private $accessTokenTTL = 14400;
    private $accessTokenCacheKey = "";

    private $client = null;

    private $systemConfig;

    // Luna算法上传图片同步OSS地址。默认无需改动
    const LUNA_OSS_BASE_URL = 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com';

    public function __construct($systemConfig = null)
    {
        if (empty($systemConfig)) {
            $systemConfig = (new LunaServiceSettingLogic())->getConfig();
        }
        $this->systemConfig = $systemConfig;
        if (empty($this->systemConfig['secret']) || empty($this->systemConfig['secret_key'])) {
            throw new BaseException("Luna算法服务配置不正确，请检查配置");
        }
        $this->secret = $this->systemConfig['secret'];
        $this->secretKey = $this->systemConfig['secret_key'];
        $this->accessTokenCacheKey = "luna_draw_token:" . $this->secretKey;
        $this->client = new Client([
            'base_uri' => $this->host,
            'timeout' => 30,
        ]);
    }

    public function getAccessToken($forceRefresh = false)
    {
        if ($forceRefresh) {
            Cache::delete($this->accessTokenCacheKey);
        }
        return CacheUtils::remember($this->accessTokenCacheKey, function () {
            $res = $this->sendRequest('POST', '/api/app/authentication', [
                'secret' => $this->secret,
                'secretKey' => $this->secretKey,
            ]);
            if (!isset($res['data']['accessToken'])) {
                throw new Exception("getAccessToken fail");
            }

            return $res['data']['accessToken'];
        }, $this->accessTokenTTL / 60 - 1, CacheTagEnum::LUNA_DRAW);
    }

    private function sendRequest($method, $uri, $params, $headers = [], $customerErrorHandler = null)
    {
        LogUtils::log("Luna作图服务请求参数", func_get_args());
        try {
            if ($method == 'GET') {
                $resp = $this->client->get($uri, [
                    'query' => $params,
                    'headers' => $headers
                ]);
            }
            if ($method == 'POST') {
                $resp = $this->client->post($uri, [
                    'json' => $params,
                    'headers' => $headers
                ]);
            }
            if ($method == 'UPLOAD') {
                $resp = $this->client->post($uri, [
                    'multipart' => [
                        [
                            'name' => 'file',
                            'contents' => \GuzzleHttp\Psr7\Utils::tryFopen($params['file_path'], 'r')
                        ],
                    ],
                    'headers' => $headers
                ]);
            }
//            echo implode(' ', [$method, $uri]), PHP_EOL;
//            echo json_encode($params, JSON_UNESCAPED_UNICODE), PHP_EOL;
//            echo $resp->getBody(), PHP_EOL;
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'timed out')) {
                $path = runtime_path('log');
                // 创建文件夹
                if (!is_dir($path)) {
                    mkdir($path, 0755, true);
                }
                $resp = $this->client->request('GET', '/', [
                    'debug' => fopen($path . 'debug.log', 'a')
                ]);
            }

            LogUtils::record($e, "请求上游失败", func_get_args());
            throw $e;
        }

        $respData = json_decode($resp->getBody(), true);
        if (empty($respData)) {
            $e = new Exception("call luna api fail: empty resp");
            LogUtils::record($e, "请求上游失败", func_get_args());
            throw $e;
        }
        if ($respData['code'] !== 1) {
            // 强制刷新TOKEN，以防止缓存的TOKEN已经失效（正常情况下不会出现）
            if ($respData['code'] == 4002) {
                $this->getAccessToken(true);
            }

            if (is_callable($customerErrorHandler)) {
                return $customerErrorHandler($respData);
            }

//            header('content-type:application/json');
//            exit(json_encode([$respData]));
            throw new Exception($respData['message']);
        }
        return $respData;
    }

    function getTagGroupList()
    {
        $res = $this->sendRequest('GET', '/api/tagGroup/get', null, [
            'JWTHEADER' => $this->getAccessToken()
        ]);
        return $res['data'];
    }

    function getTagList($tagGroupID, $is1v1 = false, $materialFilesNum = null)
    {
        $res = $this->sendRequest('GET', '/api/tagFile/get', [
            'tagGroupId' => $tagGroupID,
            'status' => $is1v1 ? 1 : 0,
            'tagPromptTypeLimit' => $materialFilesNum
        ], [
            'JWTHEADER' => $this->getAccessToken()
        ]);
        return $res['data'];
    }

    function getTagListPopular($tagGroupID, $is1v1 = false, $materialFilesNum = null)
    {
        $res = $this->sendRequest('GET', '/api/tagFile/getPopularList', [
            'tagGroupId' => $tagGroupID,
            'status' => $is1v1 ? 1 : 0,
            'tagPromptTypeLimit' => $materialFilesNum
        ], [
            'JWTHEADER' => $this->getAccessToken()
        ]);
        return $res['data'];
    }

    function getMaterialFilesByTagID($tagID, $materialFilesNum = null)
    {
        $res = $this->sendRequest('POST', '/api/tagFile/randomTagFile', [
            'tagId' => $tagID,
            'tagPromptTypeLimit' => $materialFilesNum
        ], [
            'JWTHEADER' => $this->getAccessToken()
        ]);
        return $res['data'];
    }

    function submitDrawingTask($tagFileID, $userFileID, $drawCnt, $isWatermark = 0, $prompt = null)
    {
        $res = $this->sendRequest('POST', '/api/userMessage/createSwapEnhance', [
            'tagFileId' => $tagFileID,
            'userFileId' => $userFileID,
            'sendPrompt' => $prompt,
            'fileSize' => $drawCnt,
            'isWatermark' => $isWatermark
        ], [
            'JWTHEADER' => $this->getAccessToken()
        ]);
        return $res['data'];
    }

    function submitDrawingTaskV2($drawCnt, $userFileID, $tagFileIdString = null, $tagID = null, $isWatermark = 0, $prompt = null)
    {
//        $tagFileIdString = '2447393,2408618';
        $res = $this->sendRequest('POST', '/api/userMessage/createSwapEnhance', [
            'fileSize' => 2,
            'userFileId' => $userFileID,
            'tagFileId' => $tagFileIdString,
            'tagId' => $tagID, // tagId优先级高于tagFileId
            'isWatermark' => $isWatermark,
            'sendPrompt' => $prompt,
        ], [
            'JWTHEADER' => $this->getAccessToken()
        ]);
        return $res['data'];
    }

    function submitDrawingTaskV3(FaceMappingList $faceMappingList)
    {
        $res = $this->sendRequest('POST',
            '/api/userMessage/createSwapEnhanceV3',
            $faceMappingList->toArray(),
            [
                'JWTHEADER' => $this->getAccessToken()
            ]);
        return $res['data'];
    }

    function pollTaskStatus($msgID, $isThumbnail = 0)
    {
        // todo java需要返回作图结果对应的模板id，方便定位做图出错问题，比如多只手...
        try {
            $res = $this->sendRequest('GET', '/api/userMessage/polling', [
                'messageId' => $msgID,
                'isThumbnail' => $isThumbnail,
            ], [
                'JWTHEADER' => $this->getAccessToken()
            ]);
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'frequent') || strpos($e->getMessage(), '频繁')) {
                return false;
            }
            return false;
        }
        return $res['data'];
    }

    function getMaterialFileFaceList($msgID)
    {
        try {
            $res = $this->sendRequest('GET', '/api/userMessage/getMaterialFileFaceList', [
                'id' => $msgID,
            ], [
                'JWTHEADER' => $this->getAccessToken()
            ]);
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'frequent') || strpos($e->getMessage(), '频繁')) {
                return false;
            }
            return false;
        }
        return $res['data'];
    }

    function batchGetTaskStatus($msgIDs, $isThumbnail = 0)
    {
        $res = $this->sendRequest('GET', '/api/userMessage/pollingList', [
            'messageId' => join(',', $msgIDs),
            'isThumbnail' => $isThumbnail,
        ], [
            'JWTHEADER' => $this->getAccessToken()
        ]);
        return $res['data'];
    }

    function uploadFile($filePath)
    {
        $res = $this->sendRequest('UPLOAD', '/api/userMessage/checkUserImageUpload', [
            'file_path' => $filePath,
        ], [
            'JWTHEADER' => $this->getAccessToken()
        ], function ($respData) {
            $code = BusinessErrorCodeEnum::COMMON_ERROR;
            switch ($respData['code']) {
                case 6001:
                    //  "message": "请勿重复上传!",
                    throw new BaseException('重复上传。请换一张照片', $code);
                    break;
                case 9003:
                    //  "message": "en: eyeglasses | cn: 配戴眼镜!",
                    throw new BaseException('配戴眼镜。请换一张照片', $code);
                    break;
                case 9002:
                    //  "message": "en: face occluded | cn: 脸部被遮挡!",
                    throw new BaseException('脸部被遮挡。请换一张照片', $code);
                    break;
                case 9001:
                    //  "message": "en: Face detection failed | cn: 人脸检测失败!",
                    throw new BaseException('人脸检测失败。请换一张照片', $code);
                    break;
                case 9004:
                    //  "message": "en: celebrity | cn: 名人检测失败!",
                    throw new BaseException('名人检测失败。请换一张照片', $code);
                    break;
                case 9005:
                    //  "message": "en: Safety | cn: 安全检测失败!",
                    throw new BaseException('安全检测失败。请换一张照片', $code);
                    break;
                case 9006:
                    //  "message": "en: Facial deviation is too large | cn: 脸部偏移过大!",
                    throw new BaseException('脸部偏移过大。请换一张照片', $code);
                    break;
                case 9007:
                    // en: Insufficient quality and clarity | cn: 脸部清晰度不够!
                    throw new BaseException('脸部清晰度不够。请换一张照片', $code);
                    break;
                case 9008:
                    // en: No photos of children allowed | cn: 不允许上传儿童照片!
                    throw new BaseException('不允许上传儿童照片。请换一张照片', $code);
                    break;
                default:
                    throw new BaseException(sprintf('%s: %s', $respData['message'] ?? 'Upstream error happen', $respData['code'] ?? ''), $code);
            }
            return $respData;
        });
        return $res['data'];
    }

    function uploadFileByUrl($url)
    {
        $res = $this->sendRequest('POST', '/api/userMessage/downloadUserFile', [
            'url' => $url,
        ], [
            'JWTHEADER' => $this->getAccessToken()
        ]);
        return $res['data'];
    }

    static function clearCache()
    {
        $tag = CacheTagEnum::LUNA_DRAW;
        Cache::tag($tag)->clear();
    }

}
