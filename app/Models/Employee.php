<?php
// app/Models/Employee.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Employee extends Authenticatable implements CanResetPasswordContract
{
    use HasFactory,
        Notifiable,
        HasRoles,
        SoftDeletes,
        HasApiTokens,
        CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'name',
        'pan_number',
        'designation',
        'department',
        'airport_id',
        'region_id',
        'gender',
        'date_of_birth',
        'email',
        'mobile',
        'profile_photo',
        'sports_category',
        'blood_group',
        'medical_conditions',
        'employment_status',
        'password',
        'force_password_change',
        'email_verified_at',
    ];

        /**
     * The guard name for Spatie Permission
     */
    protected $guard_name = 'employee';
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'force_password_change' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['age'];

    /**
     * Get the employee's age.
     */
    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    /**
     * Get the profile photo URL.
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }

        return null;
    }

    /**
     * Get the airport that the employee belongs to.
     */
    public function airport()
    {
        return $this->belongsTo(Airport::class);
    }

    /**
     * Get the region that the employee belongs to.
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Get the event registrations for the employee.
     */
    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Get the documents for the employee.
     */
    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    /**
     * Get the certificates for the employee.
     */
    public function certificates()
    {
        return $this->hasMany(ParticipationCertificate::class);
    }

    /**
     * Get the employee's full address.
     */
    public function getFullAddressAttribute()
    {
        $parts = [];

        if ($this->airport) {
            $parts[] = $this->airport->name;
        }

        if ($this->region) {
            $parts[] = $this->region->name . ' Region';
        }

        return implode(', ', $parts);
    }

    /**
     * Scope a query to only include active employees.
     */
    public function scopeActive($query)
    {
        return $query->where('employment_status', 'active');
    }

    /**
     * Check if employee is active.
     */
    public function isActive()
    {
        return $this->employment_status === 'active';
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }

    /**
     * Get the notification routing information for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }

    /**
     * Get the notification routing information for the database channel.
     *
     * @return array
     */
    public function routeNotificationForDatabase()
    {
        return ['id' => $this->id];
    }

    /**
     * Get the redirect route based on employee role
     */
    public function getDashboardRoute()
    {
        if ($this->force_password_change) {
            return route('employee.password.change');
        }

        if ($this->hasRole('super_admin')) {
            return route('admin.dashboard');
        } elseif ($this->hasRole('regional_sports_secretary')) {
            return route('admin.regional.dashboard');
        } elseif ($this->hasRole('airport_sports_secretary')) {
            return route('admin.airport.dashboard');
        } else {
            return route('employee.dashboard');
        }
    }
}
