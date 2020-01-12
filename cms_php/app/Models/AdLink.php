<?php

declare (strict_types=1);
namespace App\Models;

/**
 * @property int $id 
 * @property string $title 
 * @property string $image 
 * @property string $url 
 * @property int $order 
 * @property int $is_show 
 * @property string $target 
 * @property string $description 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class AdLink extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ad_link';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'title', 'image', 'url', 'order', 'is_show', 'target', 'description', 'created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'order' => 'integer', 'is_show' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}