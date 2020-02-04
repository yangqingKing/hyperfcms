<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 *​
 * RolesService.php
 *
 * User：YM
 * Date：2020/2/4
 * Time：下午9:50
 */


namespace Core\Services;


/**
 * RolesService
 * 角色服务
 * @package Core\Services
 * User：YM
 * Date：2020/2/4
 * Time：下午9:50
 *
 * @property \App\Models\SystemRole $systemRoleModel
 * @property \App\Models\SystemRolesUser $systemRolesUserModel
 */
class RolesService extends BaseService
{
    /**
     * getList
     * 条件获取角色列表
     * User：YM
     * Date：2020/2/4
     * Time：下午10:13
     * @param array $where 查询条件
     * @param array $order 排序条件
     * @param int $offset 偏移
     * @param int $limit 条数
     * @return mixed
     */
    public function getList($where = [], $order = [], $offset = 0, $limit = 0)
    {

        $list = $this->systemRoleModel->getList($where,$order,$offset,$limit);

        return $list;
    }

    /**
     * getPagesInfo
     * 获取分页信息
     * User：YM
     * Date：2020/2/4
     * Time：下午10:13
     * @param array $where
     * @return mixed
     */
    public function getPagesInfo($where = [])
    {
        $pageInfo = $this->systemRoleModel->getPagesInfo($where);

        return $pageInfo;
    }

    /**
     * saveRoles
     * 保存角色，构造数据，防止注入
     * 不接收数据库字段以外数据
     * User：YM
     * Date：2020/2/4
     * Time：下午10:57
     * @param $inputData
     * @return mixed
     */
    public function saveRoles($inputData)
    {
        $saveData = [];
        if (isset($inputData['id']) && $inputData['id']){
            $saveData['id'] = $inputData['id'];
        }

        if (isset($inputData['display_name']) && $inputData['display_name']){
            $saveData['display_name'] = $inputData['display_name'];
        }

        if (isset($inputData['name'])){
            $saveData['name'] = $inputData['name'];
        }

        if (isset($inputData['description'])){
            $saveData['description'] = $inputData['description'];
        }

        $id = $this->systemRoleModel->saveInfo($saveData);

        return $id;
    }

    /**
     * getInfo
     * 根据id获取信息
     * User：YM
     * Date：2020/2/4
     * Time：下午10:58
     * @param $id
     * @return mixed
     */
    public function getInfo($id)
    {
        $info = $this->systemRoleModel->getInfo($id);

        return $info;
    }

    /**
     * deleteInfo
     * 根据id删除信息
     * User：YM
     * Date：2020/2/4
     * Time：下午10:58
     * @param $id
     * @return mixed
     */
    public function deleteInfo($id)
    {
        $info = $this->systemRoleModel->deleteInfo($id);

        return $info;
    }

    /**
     * getRolesUserList
     * 获取角色关联用户的列表信息
     * User：YM
     * Date：2020/2/4
     * Time：下午11:31
     * @param array $where 条件
     * @param int $offset 偏移
     * @param int $limit 取值数量
     * @return mixed
     */
    public function getRolesUserList($where = [],$offset = 0, $limit = 0)
    {
        $list = $this->systemRolesUserModel->getList($where,$offset, $limit);

        return $list;
    }

    /**
     * getRoleUsers
     * 获取角色对应的用户的id集合
     * User：YM
     * Date：2020/2/4
     * Time：下午11:32
     * @param $roleId
     * @return array
     */
    public function getRoleUsers($roleId)
    {
        $where = ['system_role_id' => $roleId];

        $list = $this->getRolesUserList($where);
        $ids = array_pluck($list,'user_id');
        return $ids;
    }
}