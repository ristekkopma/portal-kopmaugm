<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SavingCycleMember extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'saving_cycle_id',
        'member_id',
        'amount',
        'paid_off_at',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'paid_off_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function savingCycle(): BelongsTo
    {
        return $this->belongsTo(SavingCycle::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

//     public function savingCycleMember()
// {
//     return $this->hasMany(SavingCycleMember::class);
// }

}
