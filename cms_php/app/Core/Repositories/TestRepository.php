<?php

declare(strict_types=1);
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


namespace App\Core\Repositories;


/**
 * TestRepository
 * 类的介绍
 * @package App\Core\Repositories
 * User：YM
 * Date：2019/11/21
 * Time：上午10:25
 * @property \APP\Core\Services\TestService $testService
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