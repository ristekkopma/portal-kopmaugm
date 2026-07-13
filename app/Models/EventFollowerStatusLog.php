<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventFollowerStatusLog extends Model
{
    protected $fillable = [
        'event_follower_id',
        'changed_by',
        'old_status',
        'new_status',
        'notes',
    ];

    public function follower(): BelongsTo
    {
        return $this->belongsTo(EventFollower::class, 'event_follower_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
