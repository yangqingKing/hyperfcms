<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Exception\Handler;

use Hyperf\Di\Annotation\Inject;
use App\Exception\BusinessException;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use App\Core\Response;
use App\Constants\StatusCode;
use App\Core\Facade\Log;

class AppExceptionHandler extends ExceptionHandler
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    /**
     * @Inject
     * @var Response
     */
    protected $response;


    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        // 异常信息处理
        $throwableMsg = sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()).PHP_EOL.$throwable->getTraceAsString();
        // 获取日志实例
        $this->logger = Log::get(requestEntry($throwable->getTrace()));



        // 判断是否由业务异常类抛出的异常
        if ($throwable instanceof BusinessException) {
            // 阻止异常冒泡
            $this->stopPropagation();
            // 业务逻辑错误日志处理
            $this->logger->warning($throwableMsg,getLogArguments());
            return $this->response->error($throwable->getCode(),$throwable->getMessage());
        }


        // 系统错误日志处理
        $this->logger->error($throwableMsg,getLogArguments());
        $msg = !empty($throwable->getMessage())?$throwable->getMessage():StatusCode::getMessage(StatusCode::ERR_SERVER);
        return $response->withAddedHeader('content-type', 'text/html; charset=utf-8')
            ->withStatus(StatusCode::ERR_SERVER)
            ->withBody(new SwooleStream($msg));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }

}
