<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 *​
 * PassportController.php
 *
 * 通行证相关
 *
 * User：YM
 * Date：2020/1/7
 * Time：下午6:43
 */


namespace App\Controller\Admin;

use App\Controller\BaseController;
use Hyperf\HttpServer\Annotation\AutoController;
use App\Exception\BusinessException;
use App\Constants\StatusCode;

/**
 * PassportController
 * 通行证相关
 * @package App\Controller\Admin
 * User：YM
 * Date：2020/1/7
 * Time：下午6:43
 *
 * @AutoController(prefix="admin_api/passport")
 *
 * @property \App\Core\Repositories\Admin\PassportRepository $passportRepo
 */
class PassportController extends BaseController
{
    /**
     * login
     * 处理登录
     * User：YM
     * Date：2020/1/8
     * Time：上午11:36
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function login()
    {
        $inputData = $this->request->all();
        $validator = $this->validation->make(
            $inputData,
            [
                'account' => 'required',
                'password' => 'required',
            ],
            [
                'account.required' => '账号不能为空',
                'password.required' => '密码不能为空',
            ]
        );
        if ($validator->fails()){
            $errorMessage = $validator->errors()->first();
            throw new BusinessException(StatusCode::ERR_EXCEPTION_USER,$errorMessage);
        }

        $data = $this->passportRepo->handleLogin($inputData);

        return $this->success($data);
    }
}