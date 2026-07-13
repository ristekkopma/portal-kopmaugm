<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventNotificationRecipient extends Model
{
    protected $fillable = [
        'batch_id',
        'event_id',
        'user_id',
        'name',
        'email',
        'status',
        'failure_reason',
        'sent_at',
    ];

    protected function casts(): array
    {
        return ['sent_at' => 'datetime'];
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(EventNotificationBatch::class, 'batch_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
