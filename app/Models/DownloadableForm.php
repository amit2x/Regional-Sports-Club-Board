<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadableForm extends Model
{
    protected $fillable = [
        'title',
        'description',
        'file_path',
        'file_type',
        'category',
        'is_active',
        'download_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
