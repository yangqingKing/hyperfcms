<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 *​
 * common.php
 *
 * 公共函数，避免功能性函数重复书写
 * 书写规范，必须使用function_exists()方法判断
 * User：YM
 * Date：2019/12/15
 * Time：上午10:27
 */

use Hyperf\Utils\Coroutine;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Swoole\Server as SwooleServer;
use Hyperf\Utils\Context;
use Hyperf\Utils\ApplicationContext;
use Hyperf\HttpMessage\Cookie\Cookie as HyperfCookie;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;


if (! function_exists('requestEntry')) {

    /**
     * requestEntry
     * 根据异常返回信息，获取请求入口（模块-控制器-方法）
     * User：YM
     * Date：2019/12/15
     * Time：上午10:53
     * @param $traceString
     * @return mixed
     */
    function requestEntry($traceString)
    {
        // 分析异常，找到路由入口，既模块，控制器-方法
        $moduleName = array_reduce(explode("\n",$traceString),function ($res,$val) {
            // 这里是分析返回数据，得出这样截取能到找匹配值，CoreMiddleware这里如果修改后需要重新处理
            $tmp = stristr($val,'CoreMiddleware.php(143):');
            if ($tmp) {
                $mArr = array_reverse(explode('\\',trim($tmp)));
                $res = str_replace(['controller','>','()'],'',strtolower($mArr[0]));
                $mStr = strtolower($mArr[1]);
                $res = $mStr == 'controller' ?$res:$mStr.'-'.$res;
            }
            return $res;
        });

        return $moduleName??'hyperf';
    }
}

if (! function_exists('getCoId')) {
    /**
     * getCoId
     * 获取当前协程id
     * User：YM
     * Date：2019/12/16
     * Time：上午12:32
     * @return int
     */
    function getCoId()
    {
        return Coroutine::id();
    }
}

if (! function_exists('getClientInfo')) {
    /**
     * getClientInfo
     * 获取请求客户端信息，获取连接的信息
     * User：YM
     * Date：2019/12/16
     * Time：上午12:39
     * @return mixed
     */
    function getClientInfo()
    {
        // 得从协程上下文取出请求
        $request =  Context::get(ServerRequestInterface::class);
        $server = make(SwooleServer::class);
        return $server->getClientInfo($request->getSwooleRequest()->fd);
    }
}

if (! function_exists('setCookies')) {
    /**
     * setCookie
     * 设置cookie
     * User：YM
     * Date：2019/12/17
     * Time：下午12:16
     * @param string $key
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @param bool $raw
     * @param null|string $sameSite
     */
    function setCookies(string $key, $value = '', $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httpOnly = true, bool $raw = false, ?string $sameSite = null)
    {
        // convert expiration time to a Unix timestamp
        if ($expire instanceof \DateTimeInterface) {
            $expire = $expire->format('U');
        } elseif (! is_numeric($expire)) {
            $expire = strtotime($expire);
            if ($expire === false) {
                throw new \RuntimeException('The cookie expiration time is not valid.');
            }
        }
        $response = ApplicationContext::getContainer()->get(ResponseInterface::class);
        $cookie = new HyperfCookie($key, (string)$value, $expire, $path, $domain, $secure, $httpOnly, $raw, $sameSite);
        $response = $response->withCookie($cookie);
        Context::set(PsrResponseInterface::class, $response);
        return;
    }
}

if (! function_exists('getCookie')) {
    /**
     * getCookie
     * 获取cookie
     * User：YM
     * Date：2019/12/17
     * Time：下午12:17
     * @param string $key
     * @param null|string $default
     * @return mixed
     */
    function getCookie(string $key,?string $default = null)
    {
        $request = ApplicationContext::getContainer()->get(RequestInterface::class);
        return $request->cookie($key, $default);
    }
}

if (! function_exists('hasCookie')) {
    /**
     * hasCookie
     * 判断cookie是否存在
     * User：YM
     * Date：2019/12/17
     * Time：下午12:20
     * @param string $key
     * @return mixed
     */
    function hasCookie(string $key)
    {
        $request = ApplicationContext::getContainer()->get(RequestInterface::class);
        return $request->hasCookie($key);
    }
}

if (! function_exists('delCookie')) {
    /**
     * delCookie
     * 删除cookie
     * User：YM
     * Date：2019/12/17
     * Time：下午12:21
     * @param string $key
     * @return bool
     */
    function delCookie(string $key) :bool
    {
        if (!hasCookie($key)) {
            return false;
        }

        setCookies($key,'', time()-1);

        return true;
    }
}