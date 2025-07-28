<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'summary',
        'experience_level',
        'current_position',
        'current_company',
        'expected_salary',
        'location',
        'linkedin_url',
        'github_url',
        'portfolio_url',
        'cv_file_path',
        'skills',
        'education',
        'experience',
        'status',
        'company_id',
        'created_by',
        'last_contact_at',
    ];

    protected $casts = [
        'skills' => 'array',
        'education' => 'array',
        'experience' => 'array',
        'expected_salary' => 'decimal:2',
        'last_contact_at' => 'datetime',
    ];

    // Helper methods
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isHired(): bool
    {
        return $this->status === 'hired';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function hasCv(): bool
    {
        return !empty($this->cv_file_path);
    }

    public function getExperienceLevelLabel(): string
    {
        return match($this->experience_level) {
            'entry' => 'Entry Level',
            'mid' => 'Mid Level',
            'senior' => 'Senior Level',
            'expert' => 'Expert Level',
            default => 'Unknown'
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'active' => 'Active',
            'inactive' => 'Inactive',
            'hired' => 'Hired',
            'rejected' => 'Rejected',
            default => 'Unknown'
        };
    }
}
