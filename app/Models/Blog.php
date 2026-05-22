<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blog';
    protected $primaryKey = 'blog_id';
    protected $fillable = ['author_name', 'author_image', 'user_id'];

    public function user()        { return $this->belongsTo(User::class, 'user_id', 'user_id'); }
    public function details()     { return $this->hasMany(BlogDetail::class, 'blog_id', 'blog_id'); }
    public function latestDetail(){ return $this->hasOne(BlogDetail::class, 'blog_id', 'blog_id')->latest(); }
}
