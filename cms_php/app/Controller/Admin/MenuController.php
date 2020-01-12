<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 *​
 * MenuController.php
 *
 * User：YM
 * Date：2020/1/11
 * Time：下午1:58
 */


namespace App\Controller\Admin;


use App\Controller\BaseController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 * MenuController
 * 菜单控制器
 * @package App\Controller\Admin
 * User：YM
 * Date：2020/1/11
 * Time：下午1:58
 *
 * @Controller(prefix="admin_api/menu")
 *
 * @property \Core\Repositories\Admin\MenuRepository $menuRepo
 */
class MenuController extends BaseController
{
    /**
     * getUserMenu
     * 获取用户权限对应的菜单
     * User：YM
     * Date：2020/1/11
     * Time：下午2:47
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @PostMapping(path="user_menu")
     */
    public function getUserMenu()
    {
        $list = $this->menuRepo->getUserMenuList();

        $data = [
            'list' => $list
        ];

        return $this->success($data);
    }

}