<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Models;

use Hyperf\DbConnection\Model\Model;
use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;

abstract class BaseModel extends Model implements CacheableInterface
{
    use Cacheable;

    /**
     * getInfo
     * 通过主键id获取信息
     * User：YM
     * Date：2020/1/8
     * Time：下午5:55
     * @param $id
     * @param bool $useCache 是否使用模型缓存
     * @return BaseModel|\Hyperf\Database\Model\Model|null
     */
    public function getInfo($id,$useCache = true)
    {
        if ($useCache === true) {
            $modelCache = self::findFromCache($id);
            return isset($modelCache) && $modelCache ? $modelCache->toArray() : [];
        }
        $query = self::where('id', $id)->first();
        return isset($query) && $query ? $query->toArray() : [];
    }

    /**
     * saveInfo
     * 创建/修改记录
     * User：YM
     * Date：2020/1/8
     * Time：下午7:49
     * @param $data 保存数据
     * @param bool $type 是否强制写入，适用于主键是规则生成情况
     * @return bool
     */
    public function saveInfo($data,$type=false)
    {
        if (isset($data['id']) && $data['id'] && !$type) {
            $query = self::query()->find($data['id']);
            unset($data['id']);
            foreach ($data as $k => $v) {
                $query->$k = $v;
            }
            $query->save();
        } else {
            $className = get_called_class();
            $query = new $className;
            foreach ($data as $k => $v) {
                $query->$k = $v;
            }
            $query->save();
        }

        return true;
    }
}
