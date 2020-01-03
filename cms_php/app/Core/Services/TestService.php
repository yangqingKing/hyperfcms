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


namespace App\Core\Services;

use Hyperf\Cache\Annotation\Cacheable;

/**
 * TestServices
 * 类的介绍
 * @package App\Core\Services
 * User：YM
 * Date：2019/11/21
 * Time：下午2:46
 *
 *
 * @property \APP\Models\Category $categoryModel
 */
class TestService extends BaseService
{
    /**
     * @Cacheable(prefix="ccategory", ttl=3600)
     */
    public function test($id)
    {
//        throw new \App\Exception\BusinessException(1000,'11');
        $tmp = $this->categoryModel->getOne($id);
        //$tmp = $this->categoryModel->findFromCache(4);
//        $tmp = 1;

        return $tmp;
    }

    public function test2($id)
    {
        $tmp = $this->categoryModel->getOne($id);

        return $tmp;
    }
}
