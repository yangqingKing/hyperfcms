<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\Utils\Context;

/**
 * RequestMiddleware
 * 接到客户端请求，通过该中间件进行一些调整
 * @package App\Middleware
 * User：YM
 * Date：2019/12/16
 * Time：上午12:13
 */
class RequestMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ServerRequestInterface
     */
    protected $request;

    public function __construct(ContainerInterface $container,ServerRequestInterface $request)
    {
        $this->container = $container;
        $this->request = $request;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 为每一个请求增加一个qid
        $request = Context::override(ServerRequestInterface::class, function (ServerRequestInterface $request) {
            $request = $request->withAddedHeader('qid', $this->getRequestId());

            return $request;
        });

        return $handler->handle($request);
    }

    /**
     * getRequestId
     * 唯一请求id
     * User：YM
     * Date：2019/11/18
     * Time：下午7:53
     * @return string
     */
    protected function getRequestId()
    {
        $tmp = $this->request->getServerParams();
        $name = strtoupper(substr(md5(gethostname()), 12, 8));
        $remote = strtoupper(substr(md5($tmp['remote_addr']),12,8));
        $ips = swoole_get_local_ip();
        $ip = strtoupper(substr(md5($ips['en0']??'127.0.0.1'), 14, 4));
        return uniqid(). '-' . $remote. '-'.$ip.'-'. $name;
    }
}