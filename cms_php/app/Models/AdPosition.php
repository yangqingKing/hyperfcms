<?php

declare (strict_types=1);
namespace App\Models;

/**
 * @property int $id 
 * @property string $title 
 * @property string $unique_identify 
 * @property string $image 
 * @property int $video_id 
 * @property string $url 
 * @property int $is_show 
 * @property string $additional_field 
 * @property string $description 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class AdPosition extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ad_position';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'title', 'unique_identify', 'image', 'video_id', 'url', 'is_show', 'additional_field', 'description', 'created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'video_id' => 'integer', 'is_show' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}