<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 *​
 * SysLogController.php
 *
 * User：YM
 * Date：2020/2/16
 * Time：上午11:28
 */


namespace App\Controller\Admin;


use App\Controller\BaseController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 * SysLogController
 * 系统日志
 * @package App\Controller\Admin
 * User：YM
 * Date：2020/2/16
 * Time：上午11:28
 *
 * @Controller(prefix="admin_api/sys_log")
 *
 * @property \Core\Repositories\Admin\SysLogRepository $sysLogRepo
 */
class SysLogController extends BaseController
{
    /**
     * index
     * 日志列表
     * User：YM
     * Date：2020/2/10
     * Time：下午10:20
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @PostMapping(path="list")
     */
    public function index()
    {
        $reqParam = $this->request->all();
        $list = $this->sysLogRepo->getList($reqParam);
        $data = [
            'pages' => $list['pages'],
            'list' => $list['data'],
        ];

        return $this->success($data);
    }
}