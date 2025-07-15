<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoinAktivitas extends Model
{
    protected $fillable = ['user_id', 'nama_kegiatan', 'jumlah_poin', 'tanggal_kegiatan'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

