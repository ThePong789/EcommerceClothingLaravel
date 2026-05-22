<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    protected $fillable = [
        'product_name', 'product_image', 'slug', 'description',
        'is_featured', 'is_active', 'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_size', 'product_id', 'size_id')
                    ->withPivot(['price', 'stock_qty']);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'product_id');
    }

    public function getMinPriceAttribute()
    {
        return $this->sizes()->min('price');
    }

    public function getAvgRatingAttribute()
    {
        return $this->reviews()->avg('rating');
    }
}
