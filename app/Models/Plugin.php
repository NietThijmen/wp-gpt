<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * The Plugin model represents a plugin entity in the application.
 * This means a WordPress plugin or maybe even the WordPress core itself.
 */
class Plugin extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'composer_registry_id',
        'description',
        'version',
        'author',
        'slug',
    ];

    public function composerRegistry(): BelongsTo
    {
        return $this->belongsTo(ComposerRegistry::class);
    }
}
