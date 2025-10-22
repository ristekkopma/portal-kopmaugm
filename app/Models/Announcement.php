<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['title','message','is_broadcast','target_user_ids','status'];
    protected $casts = ['target_user_ids' => 'array'];

    public function recipients()
    {
        return $this->hasMany(AnnouncementRecipient::class);
    }
}
