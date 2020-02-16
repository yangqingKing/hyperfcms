<?php

declare (strict_types=1);
namespace App\Models;

/**
 * @property int $id 
 * @property string $qid 
 * @property string $server_name 
 * @property string $server_addr 
 * @property string $remote_addr 
 * @property string $forwarded_for 
 * @property string $real_ip 
 * @property string $user_agent 
 * @property string $platform 
 * @property string $device 
 * @property string $browser 
 * @property string $url 
 * @property string $uri 
 * @property string $arguments 
 * @property string $method 
 * @property float $execution_time 
 * @property int $request_body_size 
 * @property int $response_body_size 
 * @property string $channel 
 * @property string $level_name 
 * @property string $message 
 * @property string $user_id 
 * @property string $referer 
 * @property int $unix_time 
 * @property string $time_day 
 * @property string $time_hour 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Log extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'qid', 'server_name', 'server_addr', 'remote_addr', 'forwarded_for', 'real_ip', 'user_agent', 'platform', 'device', 'browser', 'url', 'uri', 'arguments', 'method', 'execution_time', 'request_body_size', 'response_body_size', 'channel', 'level_name', 'message', 'user_id', 'referer', 'unix_time', 'time_day', 'time_hour', 'created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'execution_time' => 'float', 'request_body_size' => 'integer', 'response_body_size' => 'integer', 'unix_time' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
    /**
     * getList
     * 获取列表
     * User：YM
     * Date：2020/2/10
     * Time：下午10:36
     * @param array $where 查询条件
     * @param array $order 排序条件
     * @param int $offset 偏移
     * @param int $limit 条数
     * @return array
     */
    public function getList($where = [], $order = [], $offset = 0, $limit = 0)
    {
        $query = $this->query()->select($this->table . '.id', $this->table . '.qid', $this->table . '.server_name', $this->table . '.server_addr', $this->table . '.remote_addr', $this->table . '.forwarded_for', $this->table . '.real_ip', $this->table . '.user_agent', $this->table . '.platform', $this->table . '.device', $this->table . '.browser', $this->table . '.url', $this->table . '.uri', $this->table . '.arguments', $this->table . '.method', $this->table . '.execution_time', $this->table . '.request_body_size', $this->table . '.response_body_size', $this->table . '.channel', $this->table . '.level_name', $this->table . '.message', $this->table . '.user_id', $this->table . '.referer', $this->table . '.unix_time', $this->table . '.time_day', 'time_hour', $this->table . '.created_at');
        // 循环增加查询条件
        foreach ($where as $k => $v) {
            if ($k === 'start_time') {
                $query = $query->where($this->table . '.created_at', '>=', $v);
                continue;
            }
            if ($k === 'end_time') {
                $query = $query->where($this->table . '.created_at', '<=', $v);
                continue;
            }
            if ($v || $v != null) {
                $query = $query->where($this->table . '.' . $k, $v);
            }
        }
        // 追加排序
        if ($order && is_array($order)) {
            foreach ($order as $k => $v) {
                $query = $query->orderBy($this->table . '.' . $k, $v);
            }
        }
        // 是否分页
        if ($limit) {
            $query = $query->offset($offset)->limit($limit);
        }
        $query = $query->get();
        return $query ? $query->toArray() : [];
    }

    /**
     * getCount
     * 重写父类的该方法，用于条件查询计算总数
     * User：YM
     * Date：2020/2/11
     * Time：下午9:22
     * @param array $where
     * @return int
     */
    public function getCount($where = [])
    {
        $query = $this->query();
        foreach ($where as $k => $v) {
            if ($k === 'start_time') {
                $query = $query->where($this->table . '.created_at', '>=', $v);
                continue;
            }
            if ($k === 'end_time') {
                $query = $query->where($this->table . '.created_at', '<=', $v);
                continue;
            }
            $query = $query->where($this->table . '.' . $k, $v);
        }
        $query = $query->count();
        return $query > 0 ? $query : 0;
    }
}