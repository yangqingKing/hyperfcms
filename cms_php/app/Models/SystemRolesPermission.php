<?php

declare (strict_types=1);
namespace App\Models;

/**
 * @property int $system_role_id 
 * @property int $system_permission_id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class SystemRolesPermission extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_system_roles_permissions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['system_role_id', 'system_permission_id', 'created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['system_role_id' => 'integer', 'system_permission_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}