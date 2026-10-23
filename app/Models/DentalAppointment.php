<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DentalAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'appointment_schedule_id',
        'appointment_date',
        'appointment_time',
        'reason_for_visit',
        'specific_concerns',
        'status',
        'rescheduled_date',
        'rescheduled_time',
        'admin_notes',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'rescheduled_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(AppointmentSchedule::class, 'appointment_schedule_id');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'approved' => 'bg-success',
            'completed' => 'bg-primary',
            'pending' => 'bg-warning text-dark',
            'rescheduled' => 'bg-info text-dark',
            'cancelled' => 'bg-secondary',
            'rejected' => 'bg-danger',
            default => 'bg-secondary',
        };
    }
}
