<?php

declare (strict_types=1);
namespace App\Models;

/**
 * @property int $id 
 * @property string $title 
 * @property string $type 
 * @property string $image 
 * @property string $url 
 * @property int $order 
 * @property string $bg_color 
 * @property int $is_new_win 
 * @property int $is_show 
 * @property string $description 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class AdCarousel extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_ad_carousel';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'title', 'type', 'image', 'url', 'order', 'bg_color', 'is_new_win', 'is_show', 'description', 'created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'order' => 'integer', 'is_new_win' => 'integer', 'is_show' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}