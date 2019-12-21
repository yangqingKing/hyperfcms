<?php
/**
 * Created by PhpStorm.
 *​
 * StdoutLoggerFactory.php
 *
 * 日志输出工厂类
 *
 * User：YM
 * Date：2019/12/12
 * Time：下午7:17
 */


namespace App\Core\HF;

use App\Core\Facade\Log;

/**
 * StdoutLoggerFactory
 * 日志输出工厂类
 * @package App\Core\Factory
 * User：YM
 * Date：2019/12/12
 * Time：下午7:17
 */
class StdoutLoggerFactory
{

    public function __invoke()
    {
        return Log::get('hyperf');
    }

}