<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeleconsultationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'preferred_date',
        'preferred_time',
        'category',
        'description',
        'preferred_contact_method',
        'contact_details',
        'status',
        'meeting_link',
        'assigned_personnel',
        'admin_notes',
        'scheduled_at',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'scheduled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'approved' => 'bg-success',
            'scheduled' => 'bg-info text-dark',
            'completed' => 'bg-primary',
            'pending' => 'bg-warning text-dark',
            'rejected' => 'bg-danger',
            'cancelled' => 'bg-secondary',
            default => 'bg-secondary',
        };
    }
}
