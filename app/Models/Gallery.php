<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'title',
        'image_path',
        'thumbnail_path',
        'category',
        'description',
        'is_featured',
        'event_id',
        'uploaded_by',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function uploader()
    {
        return $this->belongsTo(Employee::class, 'uploaded_by');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }

    public function getThumbnailUrlAttribute()
    {
        return asset('storage/' . $this->thumbnail_path);
    }
}
