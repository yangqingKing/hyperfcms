<?php
/**
 * Created by PhpStorm.
 *​
 * EventDispatcherFactory.php
 *
 * 事件工厂类
 *
 * User：YM
 * Date：2019/12/21
 * Time：上午11:30
 */


declare(strict_types=1);

namespace App\Core\HF;

use Hyperf\Contract\StdoutLoggerInterface;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * EventDispatcherFactory
 * 改变框架的事件执行
 * @package App\Core\HF
 * User：YM
 * Date：2019/12/21
 * Time：上午11:34
 */
class EventDispatcherFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $listeners = $container->get(ListenerProviderInterface::class);
        $stdoutLogger = $container->get(StdoutLoggerInterface::class);
        return new EventDispatcher($listeners, $stdoutLogger);
    }
}
