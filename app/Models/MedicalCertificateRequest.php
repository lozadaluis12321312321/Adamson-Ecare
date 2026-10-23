<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalCertificateRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'certificate_type',
        'consultation_date',
        'purpose_of_request',
        'additional_information',
        'supporting_document_path',
        'supporting_document_name',
        'status',
        'certificate_number',
        'admin_notes',
        'released_at',
    ];

    protected $casts = [
        'consultation_date' => 'date',
        'released_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'approved', 'ready_for_release' => 'bg-info text-dark',
            'released' => 'bg-success',
            'submitted', 'under_review' => 'bg-warning text-dark',
            'additional_info_required' => 'bg-secondary',
            'rejected' => 'bg-danger',
            default => 'bg-secondary',
        };
    }
}
