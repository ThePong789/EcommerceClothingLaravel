<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $table = 'shipping';
    protected $primaryKey = 'shipping_id';
    protected $fillable = ['phone_number', 'address', 'province', 'city', 'postal_code', 'full_name', 'is_default', 'user_id'];

    public function user()   { return $this->belongsTo(User::class, 'user_id', 'user_id'); }
    public function orders() { return $this->hasMany(Order::class, 'shipping_id', 'shipping_id'); }
}
