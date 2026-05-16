<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Winner extends Model
{
    protected $fillable = [
        'event_id',
        'employee_id',
        'position',
        'category',
        'achievement',
        'prize',
        'remarks',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
