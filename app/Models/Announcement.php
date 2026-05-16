<?php
// app/Models/Announcement.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'priority',
        'status',
        'valid_from',
        'valid_until',
        'published_at',
        'created_by',
        'views_count',
        'target_audience',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'published_at' => 'datetime',
        'target_audience' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('valid_from', '<=', now())
            ->where(function($q) {
                $q->whereNull('valid_until')
                  ->orWhere('valid_until', '>=', now());
            });
    }
}
