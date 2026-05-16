<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Airport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'city',
        'state',
        'region_id',
        'address',
        'contact_person',
        'contact_number',
        'email',
        'status',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
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
