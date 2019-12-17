<?php

declare (strict_types=1);
namespace App\Models;

/**
 * @property int $id 
 * @property string $display_name 
 * @property string $name 
 * @property int $parent_id 
 * @property int $order 
 * @property string $cover 
 * @property string $description 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Category extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_category';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'display_name', 'name', 'parent_id', 'order', 'cover', 'description', 'created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'parent_id' => 'integer', 'order' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function getList()
    {
        return self::where('id',2)->first()->toArray();
    }
}