<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'id_number',
        'department_or_course',
        'contact_number',
        'emergency_contact_name',
        'emergency_contact_number',
        'gender',
        'birth_date',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isFacultyStaff(): bool
    {
        return $this->role === 'faculty_staff';
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Health Services Administrator',
            'faculty_staff' => 'Faculty / Staff',
            default => 'Student',
        };
    }

    public function dentalAppointments()
    {
        return $this->hasMany(DentalAppointment::class);
    }

    public function teleconsultationRequests()
    {
        return $this->hasMany(TeleconsultationRequest::class);
    }

    public function medicalCertificateRequests()
    {
        return $this->hasMany(MedicalCertificateRequest::class);
    }

    public function healthInquiries()
    {
        return $this->hasMany(HealthInquiry::class);
    }

    public function inquiryResponses()
    {
        return $this->hasMany(InquiryResponse::class);
    }

    public function notifications()
    {
        return $this->hasMany(AppNotification::class)->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->hasMany(AppNotification::class)->where('is_read', false)->orderBy('created_at', 'desc');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function articles()
    {
        return $this->hasMany(HealthArticle::class, 'author_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'author_id');
    }
}
