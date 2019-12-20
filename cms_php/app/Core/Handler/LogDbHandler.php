<?php
/**
 * Created by PhpStorm.
 *​
 * LogDbHandler.php
 *
 * 日志处理
 *
 * User：YM
 * Date：2019/11/29
 * Time：下午6:39
 */


namespace App\Core\Handler;

use Monolog\Handler\AbstractProcessingHandler;

/**
 * LogDbHandler
 * 日志处理，存储数据库
 * 将info、warning、notic等类型存储一个文件，debug类型存储一个文件，error类型存储一个文件
 * @package App\Core\Handler
 * User：YM
 * Date：2019/11/29
 * Time：下午6:39
 */
class LogDbHandler extends AbstractProcessingHandler
{

    public function write(array $record)
    {

//        var_dump(json_encode($record));
    }
}