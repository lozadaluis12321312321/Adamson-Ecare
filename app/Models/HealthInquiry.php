<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'subject',
        'message',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function responses()
    {
        return $this->hasMany(InquiryResponse::class)->orderBy('created_at', 'asc');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'resolved' => 'bg-success',
            'in_progress' => 'bg-info text-dark',
            'awaiting_user_response' => 'bg-primary',
            'submitted' => 'bg-warning text-dark',
            'closed' => 'bg-secondary',
            default => 'bg-secondary',
        };
    }
}
