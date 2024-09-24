<?php

namespace app;

use app\common\exception\BaseException;
use app\common\utils\LogUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use think\template\exception\TemplateNotFoundException;
use Throwable;

/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        // 使用内置的方式记录异常日志
//        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
//        return parent::render($request, $e);

        // 其他异常
        $httpCode = 500;
        $msg = $e->getMessage();
        $code = 0;
        $data = null;
        $show = 1;
        $debugData = $e->getTrace();
        if (app()->isDebug()) {
            $data = $debugData;
        }

        if ($e instanceof HttpResponseException) {
            // HTTP异常响应对象
            return $e->getResponse();
        } else if ($e instanceof HttpException) {
            // 通用异常，携带状态码
            $httpCode = $e->getStatusCode();
            $msg = $e->getMessage();
            $code = $e->getCode();
            if (strpos($msg, 'controller not exists') !== false) {
                $msg = 'invalid params';
            }
            if (strpos($msg, 'method not exists') !== false) {
                $msg = 'invalid params';
            }
        } else if ($e instanceof ValidateException) {
            // 参数校验失败
            $httpCode = 400;
            $msg = '请求参数有误';
            $code = $e->getCode();
            if (app()->isDebug()) {
                $data = $e->getError();
            }
        } else if ($e instanceof BaseException) {
            // 自定义异常
            $httpCode = $e->getHttpCode();
            $msg = $e->getErrorMessage();
            $code = $e->getErrorCode();
        } else if ($e instanceof TemplateNotFoundException) {
            LogUtils::record($e, '非法请求: template not found', [
                'request' => [
                    'uri' => $request->url(),
                    'params' => $request->all(),
                ],
                'result' => compact('code', 'show', 'msg', 'data', 'debugData')
            ]);
            $msg = '';
            $result = compact('code', 'show', 'msg', 'data');
            return json($result, 200);
        }

//        $msg .= sprintf("(%d)", $httpCode);
        if ($httpCode == 500) {
            LogUtils::record($e, '500状态码', [
                'request' => [
                    'uri' => $request->url(),
                    'params' => $request->all(),
                ],
                'result' => compact('code', 'show', 'msg', 'data', 'debugData')
            ]);
        } else if ($httpCode == 400) {
            LogUtils::record($e, '400状态码', [
                'request' => [
                    'uri' => $request->url(),
                    'params' => $request->all(),
                ],
                'result' => compact('code', 'show', 'msg', 'data', 'debugData')
            ]);
        }

        $result = compact('code', 'show', 'msg', 'data');
        return json($result, $httpCode);
    }
}
