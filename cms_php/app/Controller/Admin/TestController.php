<?php
/**
 * Created by PhpStorm.
 *​
 * TestController.php
 *
 * 文件描述
 *
 * User：YM
 * Date：2019/11/13
 * Time：上午11:32
 */


namespace App\Controller\Admin;


use App\Controller\BaseController;
use Hyperf\HttpServer\Annotation\AutoController;
use http\Exception;
use App\Exception\BusinessException;

/**
 * TestController
 * 类的介绍
 * @package App\Controller\Admin
 * User：YM
 * Date：2019/11/13
 * Time：上午11:32
 * @AutoController()
 */
class TestController extends BaseController
{
    public function index()
    {
        $user = $this->request->input('user', 'Hyperf2');
        $method = $this->request->getMethod();
//        throw new BusinessException(StatusCode::ERR_EXCEPTION);
        $data = [
            'method-test' => $method,
            'message' => "Hello {$user}.",
        ];
        $this->aaaaRepo;
        return $this->success($data);
    }
}