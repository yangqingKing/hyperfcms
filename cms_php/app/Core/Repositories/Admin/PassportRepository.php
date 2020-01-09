<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 *​
 * PassportRepository.php
 *
 * 通行证仓库
 *
 * User：YM
 * Date：2020/1/7
 * Time：下午6:56
 */


namespace Core\Repositories\Admin;


use Core\Common\Container\Auth;
use Core\Repositories\BaseRepository;
use Hyperf\Di\Annotation\Inject;

/**
 * PassportRepository
 * 通行证仓库
 * @package Core\Repositories\Admin
 * User：YM
 * Date：2020/1/7
 * Time：下午6:56
 */
class PassportRepository extends BaseRepository
{

    /**
     * @Inject()
     * @var Auth
     */
    protected $auth;

    public function handleLogin($inputData)
    {
        return $this->auth->handleLogin($inputData);
    }

}