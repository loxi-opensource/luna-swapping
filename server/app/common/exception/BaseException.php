<?php

namespace app\common\exception;

use think\Exception;

class BaseException extends Exception
{
    //HTTP状态码
    public $httpCode = 200;
    //错误具体信息
    public $errMsg;
    //自定义错误码
    public $errCode;

    public function __construct($errMsg = "Server error", $errCode = 0, Throwable $previous = null)
    {
        $this->errMsg = lang($errMsg);
        $this->errCode = $errCode;
        $this->message = $this->errMsg;
    }

    public function setHttpCode($httpCode)
    {
        $this->httpCode = $httpCode;
        return $this;
    }

    public function getHttpCode()
    {
        return $this->httpCode;
    }

    public function getErrorMessage()
    {
        return $this->errMsg;
    }

    public function getErrorCode()
    {
        return $this->errCode;
    }
}
