<?php

declare (strict_types=1);
namespace App\Models;

/**
 * @property int $id 
 * @property int $system_permission_id 
 * @property int $parent_id 
 * @property string $display_name 
 * @property string $icon 
 * @property string $url 
 * @property int $order 
 * @property string $description 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class SystemMenu extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_system_menu';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'system_permission_id', 'parent_id', 'display_name', 'icon', 'url', 'order', 'description', 'created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'system_permission_id' => 'integer', 'parent_id' => 'integer', 'order' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}