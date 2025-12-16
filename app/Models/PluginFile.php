<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class PluginFile extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $fillable = [
        'plugin_id',
        'path',
        'content',
    ];

    public function plugin(): BelongsTo
    {
        return $this->belongsTo(Plugin::class);
    }


    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'plugin' => $this->plugin,
            'path' => $this->path,
            'content' => $this->content,
        ];

    }

    public function indexableAs():string
    {
        return 'plugin_file_index';
    }
}
