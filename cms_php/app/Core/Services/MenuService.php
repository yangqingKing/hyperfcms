<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 *​
 * MenuService.php
 *
 * User：YM
 * Date：2020/1/12
 * Time：上午8:28
 */


namespace Core\Services;


/**
 * MenuService
 * 菜单服务
 * @package Core\Services
 * User：YM
 * Date：2020/1/12
 * Time：上午8:28
 *
 * @property \App\Models\SystemMenu $systemMenuModel
 * @property \Core\Common\Container\AdminPermission $adminPermission
 */
class MenuService extends BaseService
{
    /**
     * getList
     * 条件获取菜单列表
     * User：YM
     * Date：2020/1/13
     * Time：上午12:02
     * @param array $where
     * @param array $order
     * @return mixed
     */
    public function getList($where = [], $order = ['order' => 'ASC'])
    {

        $list = $this->systemMenuModel->getList($where, $order);

        return $list;
    }

    /**
     * getUserMenuList
     * 获取用户权限对应菜单列表
     * User：YM
     * Date：2020/1/12
     * Time：上午11:23
     * @param string $userId
     * @return array
     */
    public function getUserMenuList($userId = '')
    {
        $userPermissions = $this->adminPermission->getUserAllPermissions($userId);
        $menuList = $this->getList();
        foreach ($menuList as $k => &$v) {
            if (!empty($v['url'])) {
                $v['url'] = '/'.ltrim($v['url'],'/');
            }
            if ( $v['system_permission_id'] && $v['permission_name'] && !in_array($v['permission_name'],$userPermissions) ) {
                unset($menuList[$k]);
            }
        }
        unset($v);

        $tree = handleTreeList($menuList);
        foreach ($tree as $k1 => $v1) {
            if ( !(isset($v1['children']) && $v1['children']) ) {
                unset($tree[$k1]);
            }
        }
        return $tree;
    }
}