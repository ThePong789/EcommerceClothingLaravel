<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $table = 'contact_messages';
    protected $primaryKey = 'message_id';
    protected $fillable = ['first_name', 'last_name', 'email', 'subject', 'message', 'is_read'];
}
