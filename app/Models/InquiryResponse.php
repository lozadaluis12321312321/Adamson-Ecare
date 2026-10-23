<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InquiryResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'health_inquiry_id',
        'user_id',
        'message',
        'is_staff_reply',
    ];

    protected $casts = [
        'is_staff_reply' => 'boolean',
    ];

    public function inquiry()
    {
        return $this->belongsTo(HealthInquiry::class, 'health_inquiry_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
