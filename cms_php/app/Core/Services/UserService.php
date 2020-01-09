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
     * @param $id
     * @return \App\Models\BaseModel|\Hyperf\Database\Model\Model|null
     */
    public function getInfo($id)
    {
        $info = $this->userModel->getInfo($id);
        unset($info['password']);
        unset($info['session_id']);
        unset($info['deleted_at']);
        return $info;
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
}