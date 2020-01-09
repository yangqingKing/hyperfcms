<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 *​
 * Auth.php
 *
 * User：YM
 * Date：2020/1/8
 * Time：下午4:51
 */


namespace Core\Common\Container;



use Core\Services\UserService;
use Hyperf\Di\Annotation\Inject;

/**
 * Auth
 * 用户认证（登录、退出、权限）
 * @package Core\Common\Container
 * User：YM
 * Date：2020/1/8
 * Time：下午4:51
 */
class Auth
{
    /**
     * @Inject()
     * @var UserService
     */
    protected $userService;


    public function handleLogin($inputData)
    {
        $id = 'ape56d40njmwsimtwgmz62066e400000';
        return $this->userService->getInfo($id);

        $saveData = [
            'id' => 'ape56d40njmwsimtwgmz62066e402999',
            'mobile' => '19088921811',
            'username' => 'ceshii11',
            'nickname' => 'dsakfjlsd',
            'password' => 'dfsdfasdfsd',
        ];

        $id = $this->userService->saveInfo($saveData);

        return $id;
    }
}