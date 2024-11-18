<?php

namespace App\Models;

use App\Enums\MemberStatus;
use App\Enums\MemberType;
use App\Enums\RecruitmentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    /** @use HasFactory<\Database\Factories\MembershipFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'user_id',
        'type',
        'recruitment_status',
        'status',
        'registered_at',
        'interview_at',
        'joined_at',
        'change_type_at',
        'leave_at',
        'meta'
    ];

    protected function casts(): array
    {
        return [
            'type' => MemberType::class,
            'status' => 'bool',
            'recruitment_status' => RecruitmentStatus::class,
            'registered_at' => 'datetime',
            'interview_at' => 'datetime',
            'joined_at' => 'datetime',
            'change_type_at' => 'datetime',
            'leave_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function savingCycle(): HasMany
    {
        return $this->hasMany(SavingCycleUser::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'user_id');
    }

    public function scopeCandidate(Builder $query): void
    {
        $query->whereNotNull('recruitment_status')
            ->whereNotNull('registered_at');
    }

    public function scopeMember(Builder $query): void
    {
        $query->whereNotNull('joined_at')
            ->whereRecruitmentStatus(RecruitmentStatus::Approved);
    }
}
