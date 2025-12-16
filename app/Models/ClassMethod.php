<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class ClassMethod extends Model
{
    use HasFactory, SoftDeletes, Searchable;

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

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'class' => $this->class,
            'visibility' => $this->visibility,
            'name' => $this->name,
            'parameters' => $this->parameters,
            'start_line' => $this->start_line,
            'end_line' => $this->end_line,
            'phpdoc' => $this->phpdoc,
        ];
    }
    public function indexableAs():string
    {
        return 'class_methods_index';
    }
}
