<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'member_id',
        'name',
        'description',
        'path',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
