<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Hook extends Model
{
    use Searchable;

    protected $fillable = [
        'name',
        'type',
        'plugin_id',
    ];

    protected $casts = [
        'name' => 'string',
    ];

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'plugin' => $this->plugin->toSearchableArray(), // Include related plugin data
        ];
    }

    public function plugin(): BelongsTo
    {
        return $this->belongsTo(Plugin::class);
    }

    public function occurrences(): HasMany
    {
        return $this->hasMany(HookOccurance::class);

    }

    public function searchableAs(): string
    {
        return 'hook_index';
    }
}
