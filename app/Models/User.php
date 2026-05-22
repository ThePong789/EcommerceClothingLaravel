<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    protected $fillable = [
        'username', 'email', 'password', 'first_name', 'last_name',
        'profile_image', 'role_id',
    ];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['password' => 'hashed'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function isAdmin(): bool
    {
        return $this->role_id === 1;
    }

    public function isStaff(): bool
    {
        return $this->role_id === 2;
    }

    public function isUser(): bool
    {
        return $this->role_id === 3;
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'user_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }

    public function shippingAddresses()
    {
        return $this->hasMany(Shipping::class, 'user_id', 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id', 'user_id');
    }
}
