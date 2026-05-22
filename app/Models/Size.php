<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $table = 'sizes';
    public $timestamps = false;
    protected $primaryKey = 'size_id';
    protected $fillable = ['size_name'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_size', 'size_id', 'product_id')
                    ->withPivot(['price', 'stock_qty']);
    }
}
