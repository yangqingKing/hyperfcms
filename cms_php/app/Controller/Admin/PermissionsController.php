<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 *​
 * PermissionsController.php
 *
 * 文件描述
 *
 * User：YM
 * Date：2020/1/11
 * Time：下午2:43
 */


namespace App\Controller\Admin;


use App\Controller\BaseController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 * PermissionsController
 * 类的介绍
 * @package App\Controller\Admin
 * User：YM
 * Date：2020/1/11
 * Time：下午2:43
 *
 * @Controller(prefix="admin_api/permissions")
 *
 * @property \Core\Repositories\Admin\PermissionsRepository $permissionsRepo
 */
class PermissionsController extends BaseController
{
    /**
     * getUserPermissions
     * 函数的含义说明
     * User：YM
     * Date：2020/1/11
     * Time：下午2:47
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @PostMapping(path="user_permissions")
     */
    public function getUserPermissions()
    {
        $list = $this->permissionsRepo->getUserPermissionsList();

        $data = [
            'list' => $list
        ];

        return $this->success($data);
    }
}