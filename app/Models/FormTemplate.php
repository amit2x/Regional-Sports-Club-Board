<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'form_schema',
        'validation_rules',
        'is_active',
        'is_reusable',
        'created_by',
    ];

    protected $casts = [
        'form_schema' => 'array',
        'validation_rules' => 'array',
        'is_active' => 'boolean',
        'is_reusable' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function getFormFieldsAttribute()
    {
        return $this->form_schema['fields'] ?? [];
    }
}
