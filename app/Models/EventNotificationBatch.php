<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventNotificationBatch extends Model
{
    protected $fillable = [
        'event_id',
        'spreadsheet_url',
        'message',
        'status',
        'total_recipients',
        'valid_recipients',
        'failed_recipients',
        'verified_by',
        'verified_at',
        'sent_at',
        'last_error',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(EventNotificationRecipient::class, 'batch_id');
    }
}
