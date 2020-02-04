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

    /**
     * index
     * 权限列表，权限管理
     * User：YM
     * Date：2020/2/4
     * Time：下午8:23
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @PostMapping(path="list")
     */
    public function index()
    {

        $list = $this->permissionsRepo->getPermissionsList();

        $data = [
            'list' => $list
        ];

        return $this->success($data);
    }

    /**
     * store
     * User：YM
     * Date：2020/2/4
     * Time：下午9:05
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @PostMapping(path="store")
     */
    public function store()
    {
        $reqParam = $this->request->all();
        $id = $this->permissionsRepo->savePermissions($reqParam);

        return $this->success($id);
    }

    /**
     * getInfo
     * 根据id获取单条记录信息
     * User：YM
     * Date：2020/2/4
     * Time：下午9:04
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @PostMapping(path="get_info")
     */
    public function getInfo()
    {
        $reqParam = $this->request->all();
        $info = $this->permissionsRepo->getInfo($reqParam['id']);

        $data = [
            'info' => $info,
        ];

        return $this->success($data);
    }

    /**
     * orderPermissions
     * 权限的拖拽排序
     * User：YM
     * Date：2020/2/4
     * Time：下午9:03
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @PostMapping(path="order")
     */
    public function orderPermissions()
    {
        $reqParam = $this->request->all();
        $this->permissionsRepo->orderPermissions($reqParam['ids']);

        return $this->success('ok');
    }

    /**
     * destroy
     * 删除权限
     * User：YM
     * Date：2020/2/4
     * Time：下午9:02
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @PostMapping(path="delete")
     */
    public function destroy()
    {
        $reqParam = $this->request->all();
        $this->permissionsRepo->deleteInfo($reqParam['id']);

        return $this->success('ok');
    }



}