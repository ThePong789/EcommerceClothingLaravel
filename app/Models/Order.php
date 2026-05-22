<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    protected $fillable = ['order_number', 'user_id', 'shipping_id', 'total_price', 'shipping_fee', 'status', 'notes'];

    public function user()   { return $this->belongsTo(User::class, 'user_id', 'user_id'); }
    public function shipping() { return $this->belongsTo(Shipping::class, 'shipping_id', 'shipping_id'); }
    public function items()  { return $this->hasMany(OrderItem::class, 'order_id', 'order_id'); }
    public function payment(){ return $this->hasOne(Payment::class, 'order_id', 'order_id'); }

    public static function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(uniqid());
    }
}
