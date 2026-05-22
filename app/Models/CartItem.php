<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';
    public $timestamps = false;
    protected $primaryKey = 'cart_item_id';
    protected $fillable = ['cart_id', 'product_id', 'size_id', 'qty', 'price'];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'cart_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id', 'size_id');
    }

    public function getSubtotalAttribute()
    {
        return $this->price * $this->qty;
    }
}
