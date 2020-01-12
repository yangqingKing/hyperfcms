<?php

declare (strict_types=1);
namespace App\Models;

/**
 * @property int $id 
 * @property string $user_id 
 * @property string $title 
 * @property int $category_id 
 * @property string $category_ids 
 * @property string $source 
 * @property string $excerpt 
 * @property string $content 
 * @property int $cover 
 * @property string $published_time 
 * @property int $is_published 
 * @property int $is_top 
 * @property int $is_recommend 
 * @property int $order 
 * @property int $hits 
 * @property int $comment 
 * @property string $seo_title 
 * @property string $seo_keywords 
 * @property string $seo_description 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class Article extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'article';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'title', 'category_id', 'category_ids', 'source', 'excerpt', 'content', 'cover', 'published_time', 'is_published', 'is_top', 'is_recommend', 'order', 'hits', 'comment', 'seo_title', 'seo_keywords', 'seo_description', 'created_at', 'updated_at', 'deleted_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'category_id' => 'integer', 'cover' => 'integer', 'is_published' => 'integer', 'is_top' => 'integer', 'is_recommend' => 'integer', 'order' => 'integer', 'hits' => 'integer', 'comment' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}