<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class ChatConversation extends Model
{
    use SoftDeletes, Searchable;

    protected $fillable = [
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => $this->user()->get()->pluck([
                'id',
                'name',
                'email',
            ])->toArray(),
            'messages' => $this->messages()->get()->map(function ($message) {
                return [
                    'content' => $message->content,
                    'role' => $message->role,
                ];
            })->toArray(),
        ];
    }

    public function indexableAs()
    {
        return 'chat_conversations_index';
    }
}
