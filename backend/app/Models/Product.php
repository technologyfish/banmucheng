<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name_zh',
        'name_en',
        'description_zh',
        'description_en',
        'substrates',
        'specs',
        'thicknesses',
        'cover_image',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'substrates'  => 'array',
        'specs'       => 'array',
        'thicknesses' => 'array',
        'status'      => 'integer',
        'sort_order'  => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
