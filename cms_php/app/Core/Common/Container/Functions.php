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
use Hyperf\Contract\SessionInterface;
use Jenssegers\Agent\Agent;
use Hyperf\Contract\StdoutLoggerInterface;


if (! function_exists('requestEntry')) {

    /**
     * requestEntry
     * 根据异常返回信息，获取请求入口（模块-控制器-方法）
     * User：YM
     * Date：2019/12/15
     * Time：上午10:53
     * @param array $backTrace
     * @return mixed|string
     */
    function requestEntry(array $backTrace)
    {
        $moduleName = 'hyperf';

        foreach ($backTrace as $v) {
            if (stripos($v['file'],'CoreMiddleware.php') && $v['line'] == 143) {
                $tmp = array_reverse(explode('\\',trim($v['class'])));
                $module = str_replace('controller','',strtolower($tmp[1]));
                $class = str_replace('controller','',strtolower($tmp[0]));
                $function = $v['function'];
                $moduleName = $class.'-'.$function;
                if ($module) {
                    $moduleName = $module.'-'.$moduleName;
                }
                break;
            }
        }
        return $moduleName;
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

if (! function_exists('getServerLocalIp')) {
    /**
     * getServerLocalIp
     * 获取服务端内网ip地址
     * User：YM
     * Date：2019/12/19
     * Time：下午5:48
     * @return string
     */
    function getServerLocalIp()
    {
        $ip = '127.0.0.1';
        $ips = array_values(swoole_get_local_ip());
        foreach ($ips as $v) {
            if ($v && $v != $ip) {
                $ip = $v;
                break;
            }
        }

        return $ip;
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

if (! function_exists('setSessionId')) {
    /**
     * setSessionId
     * 设置sessionid
     * User：YM
     * Date：2019/12/19
     * Time：下午6:56
     * @param string $id
     */
    function setSessionId(string $id)
    {
        $session = ApplicationContext::getContainer()->get(SessionInterface::class);
        $session->setId($id);
        return ;
    }
}

if (! function_exists('getSessionId')) {
    /**
     * getSessionId
     * 获取sessionid
     * User：YM
     * Date：2019/12/19
     * Time：下午6:56
     */
    function getSessionId()
    {
        $session =  ApplicationContext::getContainer()->get(SessionInterface::class);
        return $session->getId();
    }
}

if (! function_exists('setSession')) {
    /**
     * setSession
     * 设置session
     * User：YM
     * Date：2019/12/19
     * Time：下午5:59
     * @param string $k
     * @param $v
     */
    function setSession(string $k,$v)
    {
        $session = ApplicationContext::getContainer()->get(SessionInterface::class);
        $session->set($k,$v);
        return ;
    }
}

if (! function_exists('getSession')) {
    /**
     * getSession
     * 获取session
     * User：YM
     * Date：2019/12/19
     * Time：下午7:32
     * @param string $k
     * @param null $default
     * @return mixed
     */
    function getSession(string $k,$default = null)
    {
        $session = ApplicationContext::getContainer()->get(SessionInterface::class);
        return $session->get($k,$default);
    }
}

if (! function_exists('getAllSession')) {
    /**
     * getAllSession
     * 获取所有session
     * User：YM
     * Date：2019/12/19
     * Time：下午7:32
     * @return mixed
     */
    function getAllSession()
    {
        $session = ApplicationContext::getContainer()->get(SessionInterface::class);
        return $session->all();
    }
}


if (! function_exists('hasSession')) {
    /**
     * hasSession
     * 判断session是否存在
     * User：YM
     * Date：2019/12/19
     * Time：下午7:52
     * @param string $name
     * @return bool
     */
    function hasSession(string $name)
    {
        $session = ApplicationContext::getContainer()->get(SessionInterface::class);
        return $session->has($name);
    }
}

if (! function_exists('removeSession')) {
    /**
     * removeSession
     * 从 Session 中获取并删除一条数据
     * User：YM
     * Date：2019/12/19
     * Time：下午7:54
     * @param string $name
     * @return mixed
     */
    function removeSession(string $name)
    {
        $session = ApplicationContext::getContainer()->get(SessionInterface::class);
        return $session->remove($name);
    }
}

if (! function_exists('forgetSession')) {
    /**
     * forgetSession
     * 从session中删除一条或多条数据
     * User：YM
     * Date：2019/12/19
     * Time：下午7:54
     * @param $keys string|array
     */
    function forgetSession($keys)
    {
        $session = ApplicationContext::getContainer()->get(SessionInterface::class);
        $session->forget($keys);
        return;
    }
}

if (! function_exists('clearSession')) {
    /**
     * clearSession
     * 清空当前 Session 里的所有数据
     * User：YM
     * Date：2019/12/19
     * Time：下午7:56
     */
    function clearSession()
    {
        $session = ApplicationContext::getContainer()->get(SessionInterface::class);
        return $session->clear();
    }
}

if (! function_exists('destroySession')) {
    /**
     * destroySession
     * 销毁session
     * User：YM
     * Date：2019/12/19
     * Time：下午7:56
     */
    function destroySession()
    {
        $session = ApplicationContext::getContainer()->get(SessionInterface::class);
        return $session->invalidate();
    }
}

if (! function_exists('getLogArguments')) {
    /**
     * getLogArguments
     * 获取要存储的日志部分字段，monolog以外的业务信息
     * User：YM
     * Date：2019/12/20
     * Time：下午12:57
     * @param float $executionTime 程序执行时间，运行时才能判断这里初始化为0
     * @param int $rbs 响应包体大小，初始化0，只有正常请求响应才有值
     * @return array
     */
    function getLogArguments($executionTime = null,$rbs = 0)
    {
        $request = ApplicationContext::getContainer()->get(RequestInterface::class);
        $requestHeaders = $request->getHeaders();
        $serverParams = $request->getServerParams();
        $agent = new Agent();
        $agent->setUserAgent($requestHeaders['user-agent'][0]);
        $arguments = $request->all();
        if (isset($arguments['password'])) {
            unset($arguments['password']);
        }
        return [
            'qid' => $requestHeaders['qid'][0]??'',
            'server_name' => $requestHeaders['host'][0]??'',
            'server_addr' => getServerLocalIp()??'',
            'remote_addr' => $serverParams['remote_addr']??'',
            'forwarded_for' => $requestHeaders['x-forwarded-for'][0]??'',
            'real_ip' => isset($requestHeaders['x-forwarded-for'])?$requestHeaders['x-forwarded-for'][0]:getServerLocalIp(),
            'user_agent' => $requestHeaders['user-agent'][0]??'',
            'platform' => $agent->platform()??'',
            'device' => $agent->device()??'',
            'browser' => $agent->browser()??'',
            'url' => $request->fullUrl()??'',
            'uri' => $serverParams['request_uri']??'',
            'arguments' => $arguments?json_encode($arguments):'',
            'method' => $serverParams['request_method']??'',
            'execution_time' => $executionTime,
            'request_body_size' => $requestHeaders['content-length'][0]??'',
            'response_body_size' => $rbs,
            'user_id' => getSession('user_id')??'',
            'referer' => $requestHeaders['referer']??'',
            'unix_time' => $serverParams['request_time']??'',
            'time_day' => isset($serverParams['request_time'])?date('Y-m-d',$serverParams['request_time']):'',
            'time_hour' => isset($serverParams['request_time'])?date('Y-m-d H:00:00',$serverParams['request_time']):'',
        ];
    }
}


if (! function_exists('isStdoutLog')) {
    /**
     * isStdoutLog
     * 判断日志类型是否允许输出
     * User：YM
     * Date：2019/12/21
     * Time：下午7:13
     * @param string $level
     * @return bool
     */
    function isStdoutLog(string $level)
    {
        $config = config(StdoutLoggerInterface::class, ['log_level' => []]);
        return in_array(strtolower($level), $config['log_level'], true);
    }
}

if (! function_exists('isMobileNum')) {
    /**
     * isMobileNum
     * 判断是否为手机号
     * User：YM
     * Date：2020/1/10
     * Time：上午12:21
     * @param $v
     * @return bool
     */
    function isMobileNum($v)
    {
        $search = '/^0?1[3|4|5|6|7|8][0-9]\d{8}$/';
        if (preg_match($search, $v)) {
            return true;
        } else {
            return false;
        }
    }
}

if (! function_exists('encryptPassword')) {
    /**
     * encryptPassword
     * 加密密码
     * @param string $password 用户输入的密码
     * @access public
     * @return void
     */
    function encryptPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}

if (! function_exists('checkPassword')) {
    /**
     * checkPassword
     * 检测密码
     * User：YM
     * Date：2020/1/10
     * Time：下午12:48
     * @param $value
     * @param $hashedValue
     * @return bool
     */
    function checkPassword($value, $hashedValue)
    {
        if (strlen($hashedValue) === 0) {
            return false;
        }

        return password_verify($value, $hashedValue);
    }
}

if (! function_exists('getUserUniqueId')) {
    /**
     * getUserUniqueId
     * 获取用户唯一标示，用户ID生成规则，32位
     * @access public
     * @return string
     * @throws Exception
     */
    function getUserUniqueId()
    {
        // 前缀3位
        $prefix = config('app_uid_prefix');
        $prefix = substr($prefix, 0, 3);
        //随机字符串14位
        $rand = substr(str_replace(['/', '+', '='], '', base64_encode(random_bytes(14))), 0, 14);
        //根据当前时间生成的随机字符串11位
        $uniqid = substr(uniqid(), 2);
        //当前服务器ip后4位
        $ip = getServerLocalIp();
        $ipList = explode('.', $ip);
        if(empty($ipList) || count($ipList) < 4 ){
            $ipStr = '01';
        }else{
            $ipStr = $ipList[2].$ipList[3];
        }
        $ip = dechex($ipStr);
        $ip = str_pad($ip, 6, 'f', STR_PAD_LEFT);
        if(PHP_SAPI != 'cli'){
            $ip = substr($ip, -4);
        }else{
            $ip = 'z'.substr($ip, -3);
        }

        //总共32位字符串
        return strtolower($prefix.$ip.$rand.$uniqid);
    }
}

