<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 *​
 * MenuRepository.php
 *
 * User：YM
 * Date：2020/1/11
 * Time：下午2:07
 */


namespace Core\Repositories\Admin;


use Core\Repositories\BaseRepository;

/**
 * MenuRepository
 * 类的介绍
 * @package Core\Repositories\Admin
 * User：YM
 * Date：2020/1/11
 * Time：下午2:07
 *
 * @property \Core\Common\Container\Auth $auth
 * @property \Core\Services\MenuService $menuService
 */
class MenuRepository extends BaseRepository
{
    /**
     * getUserMenuList
     * 获取用户权限可操作的菜单列表
     * User：YM
     * Date：2020/1/12
     * Time：上午8:27
     * @return mixed
     */
    public function getUserMenuList()
    {
        $userInfo = $this->auth->check();

        $list = $this->menuService->getUserMenuList($userInfo['id']);
        return $list;
    }
}