<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 *​
 * PermissionsRepository.php
 *
 * 文件描述
 *
 * User：YM
 * Date：2020/1/11
 * Time：下午2:44
 */


namespace Core\Repositories\Admin;


use Core\Repositories\BaseRepository;

/**
 * PermissionsRepository
 * 类的介绍
 * @package Core\Repositories\Admin
 * User：YM
 * Date：2020/1/11
 * Time：下午2:44
 * @property \Core\Common\Container\Auth $auth
 * @property \Core\Common\Container\AdminPermission $adminPermission
 */
class PermissionsRepository extends BaseRepository
{
    /**
     * getUserPermissionsList
     * 获取用户对应所有权限
     * User：YM
     * Date：2020/1/13
     * Time：下午4:43
     * @return array
     */
    public function getUserPermissionsList()
    {
        $userInfo = $this->auth->check();
        if (!isset($userInfo['id']) || !$userInfo['id']) {
            throw new BusinessException(StatusCode::ERR_NOT_LOGIN);
        }
        $userPermissions = $this->adminPermission->getUserAllPermissions($userInfo['id']);

        return $userPermissions;
    }
}