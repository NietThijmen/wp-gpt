<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HookOccurance extends Model
{

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
}
