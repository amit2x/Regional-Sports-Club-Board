<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_name',
        'event_code',
        'description',
        'banner_image',
        'venue',
        'region_id',
        'airport_id',
        'start_date',
        'end_date',
        'registration_last_date',
        'event_type',
        'participation_type',
        'max_participants',
        'gender_eligibility',
        'min_age',
        'max_age',
        'department_eligibility',
        'rules_regulations',
        'required_documents',
        'status',
        'is_published',
        'created_by',
        'form_template_id',
        'metadata',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_last_date' => 'datetime',
        'department_eligibility' => 'array',
        'required_documents' => 'array',
        'metadata' => 'array',
        'is_published' => 'boolean',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function airport()
    {
        return $this->belongsTo(Airport::class);
    }

    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function formTemplate()
    {
        return $this->belongsTo(FormTemplate::class);
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now());
    }

    public function scopeActive($query)
    {
        return $query->where('end_date', '>=', now())
                    ->where('status', 'published');
    }

    public function getRegistrationCountAttribute()
    {
        return $this->registrations()->count();
    }

    public function getApprovedCountAttribute()
    {
        return $this->registrations()->where('status', 'approved')->count();
    }

    public function getIsRegistrationOpenAttribute()
    {
        return $this->is_published &&
               $this->status === 'published' &&
               now()->lte($this->registration_last_date) &&
               now()->lte($this->start_date);
    }

    public function getAvailableSlotsAttribute()
    {
        if (!$this->max_participants) return null;
        return $this->max_participants - $this->approved_count;
    }
}
