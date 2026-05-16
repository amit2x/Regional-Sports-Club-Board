<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Region extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'headquarters',
        'description',
        'status',
    ];

    public function airports()
    {
        return $this->hasMany(Airport::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
