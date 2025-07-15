<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoinBelanja extends Model
{
    protected $fillable = ['user_id', 'nominal', 'usaha', 'tanggal_transaksi'];

    protected static function booted()
{
    static::saving(function ($poinBelanja) {
        $poinBelanja->total_poin = floor($poinBelanja->nominal/ 10000);
    });
}

    

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPoinAttribute(): int
    {
        return floor($this->nominal / 10000);
    }

    

}


