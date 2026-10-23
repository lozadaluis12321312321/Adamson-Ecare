<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'category',
        'priority',
        'content',
        'is_published',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'date',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'bg-danger text-white',
            'high' => 'bg-warning text-dark',
            'medium' => 'bg-primary text-white',
            'low' => 'bg-secondary text-white',
            default => 'bg-secondary text-white',
        };
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
