<?php

declare (strict_types=1);
namespace App\Models;

/**
 * @property int $id 
 * @property int $course_id 
 * @property int $lecturer_id 
 * @property string $title 
 * @property string $intro 
 * @property string $cover 
 * @property string $start_time 
 * @property string $end_time 
 * @property int $order 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class Live extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_live';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'course_id', 'lecturer_id', 'title', 'intro', 'cover', 'start_time', 'end_time', 'order', 'created_at', 'updated_at', 'deleted_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'course_id' => 'integer', 'lecturer_id' => 'integer', 'order' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}