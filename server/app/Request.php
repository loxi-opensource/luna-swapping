<?php

namespace app;

// 应用请求对象类
class Request extends \think\Request
{
    // 全局过滤规则
    protected $filter = ['trim'];

    protected $proxyServerIpHeader = [
        'HTTP_REMOTEIP', // 阿里云CLB保证是真实IP
        'HTTP_X_REAL_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'HTTP_X_CLIENT_IP', 'HTTP_X_CLUSTER_CLIENT_IP',
    ];

    // 继承重写框架的方法。去掉对上游IP白名单的限制（即无需配置proxyServerIp）
    public function ip(): string
    {
        if (!empty($this->realIP)) {
            return $this->realIP;
        }

        $this->realIP = $this->server('REMOTE_ADDR', '');

        // 如果指定了前端代理服务器IP以及其会发送的IP头
        // 则尝试获取前端代理服务器发送过来的真实IP
        $proxyIpHeader = $this->proxyServerIpHeader;
        if (count($proxyIpHeader) > 0) {
            // 从指定的HTTP头中依次尝试获取IP地址
            // 直到获取到一个合法的IP地址
            foreach ($proxyIpHeader as $header) {
                $tempIP = $this->server($header);

                if (empty($tempIP)) {
                    continue;
                }

                $tempIP = trim(explode(',', $tempIP)[0]);

                if (!$this->isValidIP($tempIP)) {
                    $tempIP = null;
                } else {
                    break;
                }
            }

            if (!empty($tempIP)) {
                $this->realIP = $tempIP;
            }
        }

        if (!$this->isValidIP($this->realIP)) {
            $this->realIP = '0.0.0.0';
        }

        return $this->realIP;
    }

}
