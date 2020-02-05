<?php

declare (strict_types=1);
namespace App\Models;

/**
 * @property string $id 
 * @property string $mobile 
 * @property string $username 
 * @property string $email 
 * @property string $nickname 
 * @property int $avatar 
 * @property string $job_number 
 * @property string $session_id 
 * @property string $password 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class User extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'mobile', 'username', 'email', 'nickname', 'avatar', 'job_number', 'session_id', 'password', 'created_at', 'updated_at', 'deleted_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['avatar' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
    /**
     * 主键id类型为字符串
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * getSearchList
     * 根据搜索条件返回list
     * User：YM
     * Date：2020/2/5
     * Time：下午2:28
     * @param string $search
     * @param array $userIds
     * @param array $notIds
     * @param int $limit
     * @return array
     */
    public function getSearchList($search = '', $userIds = [], $notIds = [], $limit = 10)
    {
        $query = $this->query()->select(
            $this->table.'.id', $this->table.'.username', $this->table.'.nickname',  $this->table.'.mobile', $this->table.'.email'
        );

        if ($search) {
            $query = $query->where(function($queryS) use ($search){
                $queryS->where('username', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            });
        }
        if ($userIds) {
            $query = $query->whereIn('id', $userIds);
        }
        if ($notIds) {
            $query = $query->whereNotIn('id', $notIds);
        }
        if ($limit) {
            $query = $query->limit($limit);
        }

        $query = $query->get();

        return $query && count($query) ? $query->toArray() : [];
    }

}