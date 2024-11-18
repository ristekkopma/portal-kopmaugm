<?php

namespace App\Models;

use App\Enums\TransactionReference;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'reference',
        'note'
    ];

    protected function casts(): array
    {
        return [
            'type' => 'bool',
            'reference' => TransactionReference::class,
        ];
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
