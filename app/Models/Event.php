<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    public const CATEGORY_URGENT = 'urgent';
    public const CATEGORY_BULANAN = 'bulanan';
    public const CATEGORY_TAHUNAN = 'tahunan';

    protected $fillable = [
        'title',
        'description',
        'image',
        'url',
        'category',
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

    public function scopeActive(Builder $query): void
    {
        $query->where('opened_at', '<=', now())
            ->where('closed_at', '>=', now());
    }

    public function scopeCategory(Builder $query, ?string $category): void
    {
        if ($category) {
            $query->where('category', $category);
        }
    }

    public static function categories(): array
    {
        return [
            self::CATEGORY_URGENT => 'Urgent',
            self::CATEGORY_BULANAN => 'Bulanan',
            self::CATEGORY_TAHUNAN => 'Tahunan',
        ];
    }
}