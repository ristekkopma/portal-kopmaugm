<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventFollower extends Model
{
    use SoftDeletes;

    protected $fillable = ['event_id', 'user_id', 'status', 'notes'];

    public static function statuses(): array
    {
        return [
            'interested' => 'Tertarik',
            'registered' => 'Terdaftar',
            'attended' => 'Hadir',
            'cancelled' => 'Batal',
        ];
    }

    protected static function booted(): void
    {
        static::updated(function (EventFollower $follower): void {
            if (! $follower->wasChanged('status')) {
                return;
            }

            $follower->statusLogs()->create([
                'changed_by' => auth()->id(),
                'old_status' => $follower->getOriginal('status'),
                'new_status' => $follower->status,
                'notes' => $follower->notes,
            ]);
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(EventFollowerStatusLog::class);
    }
}
