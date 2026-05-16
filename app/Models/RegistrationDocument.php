<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationDocument extends Model
{
    protected $fillable = [
        'event_registration_id',
        'document_type',
        'document_name',
        'file_path',
        'file_type',
        'file_size',
        'verification_status',
        'verified_by',
        'verified_at',
        'verification_notes',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function eventRegistration()
    {
        return $this->belongsTo(EventRegistration::class);
    }

    public function verifier()
    {
        return $this->belongsTo(Employee::class, 'verified_by');
    }

    public function getDownloadUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}
