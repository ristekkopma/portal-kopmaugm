<?php

namespace App\Models;

use App\Enums\TransactionReference;
use App\Observers\SavingCycleObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(SavingCycleObserver::class)]
class SavingCycle extends Model
{
    protected $fillable = [
        'name',
        'start_at',
        'end_at',
        'reference',
        'default_amount'
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'reference' => TransactionReference::class,
        ];
    }

    public function savingCycleMember(): HasMany
    {
        return $this->hasMany(SavingCycleMember::class);
    }
}
