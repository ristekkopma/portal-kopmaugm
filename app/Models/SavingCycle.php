<?php

namespace App\Models;

use App\Enums\TransactionReference;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SavingCycle extends Model
{
    protected $fillable = [
        'name',
        'start_at',
        'end_at',
        'reference'
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'reference' => TransactionReference::class,
        ];
    }

    public function savigCycleUser(): HasMany
    {
        return $this->hasMany(SavingCycleUser::class);
    }
}
