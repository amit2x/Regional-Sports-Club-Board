<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
    protected $fillable = [
        'employee_id',
        'document_type',
        'document_name',
        'file_path',
        'file_type',
        'file_size',
        'verification_status',
        'verified_by',
        'verified_at',
        'verification_notes',
        'expiry_date',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'expiry_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function verifier()
    {
        return $this->belongsTo(Employee::class, 'verified_by');
    }
}
