<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    protected $fillable = [
        'user_id',
        'recruitment_cycle_id',
        'date_of_birth',
        'gender',
        'county_of_origin',
        'county_of_residence',
        'phone_number',
        'education_level',
        'preferred_testing_center',
        'status',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recruitmentCycle(): BelongsTo
    {
        return $this->belongsTo(RecruitmentCycle::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
