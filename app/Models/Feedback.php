<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'name',
        'email',
        'rating',
        'feedback',
        'category',
        'ip_address',
        'status',
    ];
}
