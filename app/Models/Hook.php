<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hook extends Model
{

    protected $fillable = [
        'name',
        'type',
        'plugin_id',
    ];

    protected $casts = [
        'name' =>  'string',
    ];

    public function plugin(): BelongsTo
    {
        return $this->belongsTo(Plugin::class);
    }
}
