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
 */
class PermissionsRepository extends BaseRepository
{
    public function getUserPermissionsList()
    {
        return [];
    }
}