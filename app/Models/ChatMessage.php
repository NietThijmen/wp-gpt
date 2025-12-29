<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'chat_conversation_id',
        'content',
        'role',
    ];

    public function chatConversation(): BelongsTo
    {
        return $this->belongsTo(ChatConversation::class);
    }
}
