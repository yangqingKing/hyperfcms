<?php

declare (strict_types=1);
namespace App\Models;

/**
 * @property int $id 
 * @property string $name 
 * @property string $display_name 
 * @property string $description 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class SystemRole extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_system_roles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'display_name', 'description', 'created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}