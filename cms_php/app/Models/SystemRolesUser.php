<?php

declare (strict_types=1);
namespace App\Models;

/**
 * @property int $system_role_id 
 * @property string $user_id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class SystemRolesUser extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_roles_user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['system_role_id', 'user_id', 'created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['system_role_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}