<?php
/**
 * Created by PhpStorm.
 *​
 * TestRepository.php
 *
 * 文件描述
 *
 * User：YM
 * Date：2019/11/21
 * Time：上午10:25
 */


namespace App\Repositories;


/**
 * TestRepository
 * 类的介绍
 * @package App\Repositories
 * User：YM
 * Date：2019/11/21
 * Time：上午10:25
 * @property \APP\Services\TestService $testService
 */
class TestRepository extends BaseRepository
{

    public function test()
    {
//        throw new \App\Exception\BusinessException(1000,'11');

        
        $tmp = $this->testService->test();

        return $tmp;
    }

}