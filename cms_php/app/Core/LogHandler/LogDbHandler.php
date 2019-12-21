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


namespace App\Core\LogHandler;

use Monolog\Handler\AbstractProcessingHandler;
use Hyperf\DbConnection\Db;
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
     * Date：2019/12/20
     * Time：下午6:02
     * @param array $record
     */
    public function write(array $record)
    {
        $saveData = $record['context'];
        $saveData['channel'] = $record['channel'];
        $saveData['message'] = is_array($record['message'])?json_encode($record['message']):$record['message'];
        $saveData['level_name'] = $record['level_name'];

//        if ($saveData['channel']  == 'hyperf') {
//            return;
//        }

       var_dump($record['message']);


//        Db::table('_logs')->insert($saveData);
//        Log::create($saveData);
    }
}