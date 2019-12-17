<?php
/**
 * Created by PhpStorm.
 *​
 * TestServices.php
 *
 * 文件描述
 *
 * User：YM
 * Date：2019/11/21
 * Time：下午2:46
 */


namespace App\Services;


/**
 * TestServices
 * 类的介绍
 * @package App\Services
 * User：YM
 * Date：2019/11/21
 * Time：下午2:46
 *
 *
 * @property \APP\Models\Category $categoryModel
 */
class TestService extends BaseService
{
    public function test()
    {
//        throw new \App\Exception\BusinessException(1000,'11');
        $tmp = $this->categoryModel->getList();
//        $tmp = $this->categoryModel->findFromCache(4);
//        $tmp = 1;

        return $tmp;
    }
}