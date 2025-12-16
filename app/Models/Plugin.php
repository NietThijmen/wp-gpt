<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

/**
 * The Plugin model represents a plugin entity in the application.
 * This means a WordPress plugin or maybe even the WordPress core itself.
 */
class Plugin extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'version',
        'author',
        'slug',
    ];

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'version' => $this->version,
            'author' => $this->author,
            'slug' => $this->slug,
        ];
    }

    public function searchableAs(): string
    {
        return 'plugin_index';
    }
}
