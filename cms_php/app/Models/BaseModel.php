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
     * 通过主键id/ids获取信息
     * User：YM
     * Date：2020/1/8
     * Time：下午5:55
     * @param $id
     * @param bool $useCache 是否使用模型缓存
     * @return BaseModel|\Hyperf\Database\Model\Model|null
     */
    public function getInfo($id,$useCache = true)
    {
        $instance = make(get_called_class());

        if ($useCache === true) {
            $modelCache = is_array($id)?$instance->findManyFromCache($id):$instance->findFromCache($id);
            return isset($modelCache) && $modelCache ? $modelCache->toArray() : [];
        }

        $query = $instance->query()->find($id);
        return $query ? $query->toArray() : [];
    }

    /**
     * saveInfo
     * 创建/修改记录
     * User：YM
     * Date：2020/1/8
     * Time：下午7:49
     * @param $data 保存数据
     * @param bool $type 是否强制写入，适用于主键是规则生成情况
     * @return null
     */
    public function saveInfo($data,$type=false)
    {
        $id = null;
        $instance = make(get_called_class());
        if (isset($data['id']) && $data['id'] && !$type) {
            $id = $data['id'];
            unset($data['id']);
            $query = $instance->query()->find($id);
            foreach ($data as $k => $v) {
                $query->$k = $v;
            }
            $query->save();
        } else {
            foreach ($data as $k => $v) {
                if ($k === 'id') {
                    $id = $v;
                }
                $instance->$k = $v;
            }
            $instance->save();
            if (!$id) {
                $id = $instance->id;
            }
        }

        return $id;
    }

    /**
     * getInfoByWhere
     * 根据条件获取结果
     * User：YM
     * Date：2020/1/9
     * Time：下午10:24
     * @param $where
     * @param bool $type 是否查询多条
     * @return array
     */
    public function getInfoByWhere($where,$type=false)
    {
        $instance = make(get_called_class());
        foreach ($where as $k => $v) {
            $instance = is_array($v)?$instance->where($k,$v[0],$v[1]):$instance->where($k,$v);
        }

        $instance = $type?$instance->get():$instance->first();

        return $instance ? $instance->toArray() : [];
    }
}
