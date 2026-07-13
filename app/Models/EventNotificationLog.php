<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventNotificationLog extends Model
{
    protected $fillable = ['batch_id', 'event_id', 'user_id', 'action', 'metadata'];

    protected function casts(): array
    {
        return ['metadata' => 'array'];
    }
}
