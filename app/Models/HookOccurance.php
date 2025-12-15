<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class HookOccurance extends Model
{

    use Searchable;
    protected $fillable = [
        'hook_id',
        'file_path',
        'line',
        'args',
        'surroundingCode',
        'class',
        'method',
        'class_phpdoc',
    ];

    public function hook(): BelongsTo
    {
        return $this->belongsTo(Hook::class);
    }

    protected function casts(): array
    {
        return [
            'args' => 'array',
        ];
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'hook' => $this->hook->name,
            'plugin' => $this->hook->plugin->name,
            'file_path' => $this->file_path,
            'line' => $this->line,
            'args' => $this->args,
            'surroundingCode' => $this->surroundingCode,
            'class' => $this->class,
            'method' => $this->method,
            'class_phpdoc' => $this->class_phpdoc,
        ];
    }

    public function searchableAs(): string
    {
        return 'hook_occurance_index';
    }
}

