<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageReply extends Model
{
    protected $fillable = ['parent_message_id', 'reply_message_id'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'parent_message_id');
    }

    public function reply(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'reply_message_id');
    }
}
