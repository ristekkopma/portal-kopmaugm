<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\Marrital;
use App\Enums\Religion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $table = 'user_profile';

    protected $fillable = [
        'user_id',
        'nickname',
        'dob',
        'pob',
        'gender',
        'marrital',
        'religion',
        'instance',
        'faculty',
        'major',
        'nim',
        'work',
        'last_education',
        'address',
        'meta'
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'datetime:Y-m-d',
            'marrital' => Marrital::class,
            'gender' => Gender::class,
            'religion' => Religion::class,
            'meta' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
