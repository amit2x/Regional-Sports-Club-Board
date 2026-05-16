<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventRegistration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'registration_number',
        'event_id',
        'employee_id',
        'status',
        'form_data',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'documents_verified',
        'verification_notes',
    ];

    protected $casts = [
        'form_data' => 'array',
        'verification_notes' => 'array',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'documents_verified' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    public function rejector()
    {
        return $this->belongsTo(Employee::class, 'rejected_by');
    }

    public function documents()
    {
        return $this->hasMany(RegistrationDocument::class);
    }

    public static function generateRegistrationNumber()
    {
        $prefix = 'REG';
        $year = date('Y');
        $random = strtoupper(substr(uniqid(), -6));
        return "{$prefix}-{$year}-{$random}";
    }
}
