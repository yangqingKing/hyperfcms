<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 *​
 * Auth.php
 *
 * User：YM
 * Date：2020/1/8
 * Time：下午4:51
 */


namespace Core\Common\Container;



use Core\Services\UserService;
use Hyperf\Di\Annotation\Inject;
use App\Constants\StatusCode;
use App\Exception\BusinessException;

/**
 * Auth
 * 用户认证（登录、退出、权限）
 * @package Core\Common\Container
 * User：YM
 * Date：2020/1/8
 * Time：下午4:51
 */
class Auth
{
    // 登录标识key
    const LOGIN_TAG = 'LOGIN_AUTH';

    /**
     * @Inject()
     * @var UserService
     */
    protected $userService;

    /**
     * handleLogin
     * 处理登录
     * User：YM
     * Date：2020/1/10
     * Time：上午12:44
     * @param $inputData
     * @return array|bool
     */
    public function handleLogin($inputData)
    {
        $key = isMobileNum($inputData['account'])?'mobile':'username';
        $where = [
            $key => $inputData['account']
        ];
        $row = $this->userService->getInfoByWhere($where);
        if (!$row) {
            throw new BusinessException(StatusCode::ERR_USER_ABSENT);
        }
        if (!isset($row['password']) || !$row['password'] || !checkPassword($inputData['password'], $row['password'])) {
            throw new BusinessException(StatusCode::ERR_EXCEPTION_USER);
        }

        $this->loginByUid($row['id'], $inputData['remember']??false);
        $userInfo = $this->handleUserInfo($row);

        return array_merge($userInfo,['sid' => getSessionId()]);
    }

    /**
     * loginByUid
     * 登录
     * User：YM
     * Date：2020/1/10
     * Time：上午1:14
     * @param $uid
     * @param bool $remember
     * @return bool
     */
    public function loginByUid($uid, $remember=false)
    {
        $authId = $this->encodeUid($uid);
        setSession(self::LOGIN_TAG, $authId);
        return true;
    }

    /**
     * handleUserInfo
     * 处理登录成功后返回的用户数据
     * User：YM
     * Date：2020/1/10
     * Time：上午12:43
     * @param $info
     * @return array
     */
    public function handleUserInfo($info)
    {
        $res = [
            'id' => $info['id']??'',
            'mobile' => $info['mobile']??'',
            'username' => $info['username']??'',
            'nickname' => $info['nickname']??'',
            'avatar' => $info['avatar']??'',
            'created_at' => $info['created_at']??''
        ];
        return $res;
    }

    /**
     * encodeUid
     * 编码uid
     * @param mixed $uid
     * @access public
     * @return void
     */
    public function encodeUid($uid)
    {
        $uid = base64_encode("Y.{$uid}M");
        return $uid;
    }

    /**
     * decodeUid
     * 解码uid
     * @param mixed $uid
     * @access public
     * @return void
     */
    public function decodeUid($uid)
    {
        $uid = base64_decode($uid);
        $uid = substr($uid, 2, -1);
        return $uid;
    }

}