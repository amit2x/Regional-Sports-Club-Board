<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParticipationCertificate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'certificate_number',
        'event_registration_id',
        'employee_id',
        'event_id',
        'issue_date',
        'pdf_path',
        'qr_code_path',
        'verification_url',
        'generated_by',
        'metadata',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'metadata' => 'array',
    ];

    public function registration()
    {
        return $this->belongsTo(EventRegistration::class, 'event_registration_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function generator()
    {
        return $this->belongsTo(Employee::class, 'generated_by');
    }
}
