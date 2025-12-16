<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'plugin_file_id',
        'className',
        'phpdoc',
    ];

    public function pluginFile(): BelongsTo
    {
        return $this->belongsTo(Plugin::class, 'plugin_file_id');
    }

    public function methods(): HasMany
    {
        return $this->hasMany(ClassMethod::class, 'file_class_id');
    }
}
