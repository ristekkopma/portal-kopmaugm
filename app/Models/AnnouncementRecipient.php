<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementRecipient extends Model
{
    protected $fillable = [
        'announcement_id','user_id','whatsapp','status','sent_at',
        'attempts','last_error','waha_message_id','waha_raw',
    ];
    protected $casts = ['waha_raw' => 'array'];

    public function announcement(){ return $this->belongsTo(Announcement::class); }
    public function user(){ return $this->belongsTo(User::class); }
}
