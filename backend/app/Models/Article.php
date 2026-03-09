<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'category_id',
        'title',
        'title_en',
        'description',
        'description_en',
        'cover_image',
        'content',
        'content_en',
        'published_at',
        'is_active',
        'is_frontend_visible',
        'sort_order',
    ];

    protected $casts = [
        'is_active'           => 'boolean',
        'is_frontend_visible' => 'boolean',
        'sort_order'          => 'integer',
        'published_at'        => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }
}
