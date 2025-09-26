<?php

namespace App\Models;

use App\Enums\MemberStatus;
use App\Enums\MemberType;
use App\Enums\RecruitmentStatus;
use App\Observers\MemberObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'member_id',
        'name',
        'description',
        'path',
    ];

        public function scopeMember(Builder $query): void
    {
        $query->whereNotNull('joined_at')
            ->whereRecruitmentStatus(RecruitmentStatus::Approved);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
