<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatUser extends Model
{
    use HasFactory;

    protected $table = 'chat_user';
    public $timestamps = false;

    protected $fillable = [
        'chat_id',
        'user_id',
        'joined_at',
    ];
}
