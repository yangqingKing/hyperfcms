<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 *​
 * UserService.php
 *
 * 文件描述
 *
 * User：YM
 * Date：2020/1/8
 * Time：下午5:16
 */


namespace Core\Services;


/**
 * UserService
 * 类的介绍
 * @package Core\Services
 * User：YM
 * Date：2020/1/8
 * Time：下午5:16
 *
 * @property \APP\Models\User $userModel
 */
class UserService extends BaseService
{
    /**
     * getInfo
     * 获取用户数据
     * User：YM
     * Date：2020/1/8
     * Time：下午7:52
     * @param $id 可以传入数组
     * @return \App\Models\BaseModel|\Hyperf\Database\Model\Model|null
     */
    public function getInfo($id)
    {
        $res = $this->userModel->getInfo($id,false);
        if (count($res) == count($res,1)) {
            unset($res['password']);
            unset($res['session_id']);
            unset($res['deleted_at']);
        } else {
            foreach ($res as &$v) {
                unset($v['password']);
                unset($v['session_id']);
                unset($v['deleted_at']);
            }
            unset($v);
        }

        return $res;
    }

    /**
     * saveInfo
     * 创建/编辑用户信息
     * User：YM
     * Date：2020/1/8
     * Time：下午7:57
     * @param $data
     * @param bool $type 是否强制创建
     * @return mixed
     */
    public function saveInfo($data, $type = false)
    {
        $id = $this->userModel->saveInfo($data,$type);

        return $id;
    }

    /**
     * getInfoByWhere
     * 根据条件获取用户信息
     * User：YM
     * Date：2020/1/10
     * Time：上午12:32
     * @param $where
     * @param bool $type 是否多条
     * @return array
     */
    public function getInfoByWhere($where,$type=false)
    {
        $res = $this->userModel->getInfoByWhere($where,$type);

        return $res;
    }


}