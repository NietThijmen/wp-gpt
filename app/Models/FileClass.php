<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class FileClass extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $fillable = [
        'plugin_file_id',
        'className',
        'phpdoc',
    ];

    public function pluginFile(): BelongsTo
    {
        return $this->belongsTo(PluginFile::class, 'plugin_file_id');
    }

    public function plugin(): BelongsTo
    {
        return $this->pluginFile->belongsTo(Plugin::class, 'plugin_id');
    }

    public function methods(): HasMany
    {
        return $this->hasMany(ClassMethod::class, 'file_class_id');
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'plugin_file' => $this->pluginFile,
            'className' => $this->className,
            'phpdoc' => $this->phpdoc,
        ];
    }

    public function indexableAs():string
    {
        return 'file_class_index';
    }
}
