<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecruitmentCycle extends Model
{
    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function isOpen(): bool
    {
        $today = now()->startOfDay();
        return $this->is_active && $today->between($this->start_date, $this->end_date);
    }
}
