<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'class_method_id',
        'visibility',
        'name',
        'parameters',
        'start_line',
        'end_line',
        'phpdoc',
    ];

    public function class()
    {
        return $this->belongsTo(FileClass::class, 'file_class_id');
    }

    protected function casts(): array
    {
        return [
            'parameters' => 'array',
        ];
    }
}
