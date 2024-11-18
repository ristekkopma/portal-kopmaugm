<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'images',
        'url',
        'opened_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    protected $appends = ['isActive'];

    public function scopeActive(Builder $query): void
    {
        $query->where('opened_at', '<=', now())
            ->where('closed_at', '>=', now());
    }

    protected function isActive(): Attribute
    {
        return new Attribute(
            get: fn(): bool => $this->opened_at <= now() && $this->closed_at >= now(),
        );
    }
}
