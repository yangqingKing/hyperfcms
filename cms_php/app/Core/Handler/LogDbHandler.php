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
use Symfony\Component\Console\Output\ConsoleOutput;
use App\Models\Log;

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
    /**
     * write
     * 记录日志
     * User：YM
     * Date：2019/12/21
     * Time：下午4:15
     * @param array $record
     * @return bool|void
     */
    public function write(array $record)
    {
        // 判断系统允许日志类型
        if ( ! isStdoutLog($record['level_name']) ) {
            return false;
        }

        $saveData = $record['context'];
        $saveData['channel'] = $record['channel'];
        $saveData['message'] = is_array($record['message'])?json_encode($record['message']):$record['message'];
        $saveData['level_name'] = $record['level_name'];

        // db驱动是，允许打印框架日志，则直接输出
        if (env('HF_LOG', false) && $record['channel'] == 'hyperf') {
            $output = new ConsoleOutput();
            $output->writeln($record['formatted']);
        }

        // db驱动，不记录框架日志，框架启动时死循环，原因不明
        if ($saveData['channel'] == 'hyperf') {
            return;
        }
        Log::create($saveData);
    }
}