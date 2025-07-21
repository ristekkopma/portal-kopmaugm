<?php

namespace App\Models;

use App\Enums\MemberStatus;
use App\Enums\MemberType;
use App\Enums\RecruitmentStatus;
use App\Observers\MemberObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([MemberObserver::class])]
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
            'status' => MemberStatus::class,
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

    public function savingCycleMember(): HasMany
    {
        return $this->hasMany(SavingCycleMember::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'user_id');
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'user_id', 'user_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function savingCycleMembers(): HasMany
    {
        return $this->hasMany(SavingCycleMember::class);
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
    public function scopeActiveMember(Builder $query): void
    {
        $query->whereNotNull('joined_at')
            ->whereNull('leave_at')
            ->whereStatus(true);
    }
}
