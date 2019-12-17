<?php

declare (strict_types=1);
namespace App\Models;

/**
 * @property int $id 
 * @property string $user_id 
 * @property string $famous_nickname 
 * @property string $intro 
 * @property string $image 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Lecturer extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_lecturer';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'famous_nickname', 'intro', 'image', 'created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}