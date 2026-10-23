<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_date',
        'start_time',
        'end_time',
        'max_slots',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'is_active' => 'boolean',
        'max_slots' => 'integer',
    ];

    public function appointments()
    {
        return $this->hasMany(DentalAppointment::class, 'appointment_schedule_id');
    }

    public function bookedCount(): int
    {
        return $this->appointments()
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->count();
    }

    public function availableSlots(): int
    {
        $remaining = $this->max_slots - $this->bookedCount();
        return max(0, $remaining);
    }

    public function isFullyBooked(): bool
    {
        return $this->availableSlots() <= 0;
    }
}
