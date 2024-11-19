<?php

namespace App\Models;

use App\Enums\TransactionReference;
use App\Observers\TransactionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([TransactionObserver::class])]
class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'reference',
        'transacted_at',
        'note'
    ];

    protected function casts(): array
    {
        return [
            'type' => 'bool', //1 = debit, 0 = credit
            'reference' => TransactionReference::class,
            'transacted_at' => 'datetime',
        ];
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
