<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogDetail extends Model
{
    protected $table = 'blog_details';
    protected $primaryKey = 'blog_detail_id';
    protected $fillable = ['title', 'subtitle', 'description', 'blog_image', 'slug', 'tags', 'is_published', 'blog_id'];

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id', 'blog_id');
    }
}
