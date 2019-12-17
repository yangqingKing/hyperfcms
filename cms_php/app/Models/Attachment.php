<?php

declare (strict_types=1);
namespace App\Models;

/**
 * @property int $id 
 * @property string $title 
 * @property string $filename 
 * @property string $path 
 * @property string $type 
 * @property int $size 
 * @property string $user_id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Attachment extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_attachment';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'title', 'filename', 'path', 'type', 'size', 'user_id', 'created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'size' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}