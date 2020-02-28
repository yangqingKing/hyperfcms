<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 *​
 * UploadController.php
 *
 * User：YM
 * Date：2020/2/6
 * Time：下午8:17
 */


namespace App\Controller\Admin;


use App\Constants\StatusCode;
use App\Controller\BaseController;
use App\Exception\BusinessException;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 * UploadController
 * 上传文件
 * @package App\Controller\Admin
 * User：YM
 * Date：2020/2/6
 * Time：下午8:17
 *
 * @Controller(prefix="admin_api/upload")
 *
 * @property \Core\Repositories\Admin\UploadRepository $uploadRepo
 */
class UploadController extends BaseController
{
    /**
     * getUploadToken
     * 获取上传凭证
     * User：YM
     * Date：2020/2/6
     * Time：下午8:33
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @PostMapping(path="get_upload_token")
     */
    public function getUploadToken()
    {
        $tokenInfo = $this->uploadRepo->getUploadToken();

        return $this->success($tokenInfo);
    }

    /**
     * uploadFile
     * 函数的含义说明
     * User：YM
     * Date：2020/2/25
     * Time：下午11:56
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @PostMapping(path="file")
     */
    public function uploadFile()
    {
        $reqParam = $this->request->all();
        $files = $this->request->getUploadedFiles();
        if(empty($files)){
            throw new BusinessException(StatusCode::ERR_EXCEPTION,'上传文件为空');
        }
        $res = $this->uploadRepo->uploadFiles($files,$reqParam);

        return $this->success($res);
    }
}