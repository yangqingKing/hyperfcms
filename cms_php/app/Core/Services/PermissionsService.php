<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 *​
 * PermissionsService.php
 *
 * User：YM
 * Date：2020/2/3
 * Time：下午4:08
 */


namespace Core\Services;


/**
 * PermissionsService
 * 权限服务
 * @package Core\Services
 * User：YM
 * Date：2020/2/3
 * Time：下午4:08
 *
 * @property \App\Models\SystemPermission $systemPermissionModel
 */
class PermissionsService extends BaseService
{

    /**
     * getList
     * 条件获取权限列表
     * User：YM
     * Date：2020/2/3
     * Time：下午4:23
     * @param array $where
     * @param array $order
     * @return array
     */
    public function getList($where = [], $order = ['order' => 'ASC'])
    {

        $list = $this->systemPermissionModel->getList($where, $order);

        return $list;
    }

    /**
     * getPermissionsTreeList
     * 获取树形结构的权限列表
     * User：YM
     * Date：2020/2/3
     * Time：下午4:24
     * @return array
     */
    public function getPermissionsTreeList()
    {
        $list = $this->getList();

        $tree = handleTreeList($list);

        return $tree;
    }

    /**
     * getParentIds
     *  获取父级们的id，组成数组
     * User：YM
     * Date：2020/2/3
     * Time：下午5:08
     * @param $id
     * @return array
     */
    public function getParentIds($id)
    {
        $arr = [];
        $info = $this->systemPermissionModel->getInfo($id);
        if ($info && $info['parent_id']) {
            $arr[] = $info['parent_id'];
            $arr = array_merge($this->getParentIds($info['parent_id']),$arr);
        }

        return $arr;
    }


}