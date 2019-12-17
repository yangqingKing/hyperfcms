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
    protected $table = '_user';
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
}