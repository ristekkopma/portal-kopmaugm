<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Event extends Model
{
    use SoftDeletes;

    public const CATEGORY_URGENT = 'urgent';
    public const CATEGORY_BULANAN = 'bulanan';
    public const CATEGORY_TAHUNAN = 'tahunan';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'thumbnail',
        'banner',
        'organizer_name',
        'organizer_logo',
        'url',
        'category',
        'opened_at',
        'closed_at',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'event_type',
        'registration_url',
        'registration_deadline',
        'contact_person',
        'rundown',
        'terms',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
            'event_date' => 'date',
            'registration_deadline' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Event $event): void {
            if (blank($event->slug)) {
                $event->slug = static::uniqueSlug($event->title);
            }
        });

        static::updating(function (Event $event): void {
            if ($event->isDirty('title') && blank($event->slug)) {
                $event->slug = static::uniqueSlug($event->title, $event->id);
            }
        });
    }

    public function followers(): HasMany
    {
        return $this->hasMany(EventFollower::class);
    }

    public function activeFollowers(): HasMany
    {
        return $this->hasMany(EventFollower::class)
            ->where('status', '!=', 'cancelled');
    }

    public function interestedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_followers')->withTimestamps();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(EventReview::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getDisplayImageAttribute(): string
    {
        $path = $this->banner ?: $this->thumbnail ?: $this->image;

        return $path ? asset('storage/' . $path) : asset('images/logo.png');
    }

    public function getDisplayOrganizerLogoAttribute(): string
    {
        return $this->organizer_logo
            ? asset('storage/' . $this->organizer_logo)
            : asset('images/logo.png');
    }

    public function getSafeRegistrationUrlAttribute(): ?string
    {
        if (! filter_var($this->registration_url, FILTER_VALIDATE_URL)) {
            return null;
        }

        return in_array(parse_url($this->registration_url, PHP_URL_SCHEME), ['http', 'https'], true)
            ? $this->registration_url
            : null;
    }

    public function getScheduleStartAttribute(): ?Carbon
    {
        if ($this->event_date && $this->start_time) {
            return Carbon::parse($this->event_date->format('Y-m-d') . ' ' . $this->start_time);
        }

        return $this->opened_at ?: ($this->event_date?->startOfDay());
    }

    public function getScheduleEndAttribute(): ?Carbon
    {
        if ($this->event_date && $this->end_time) {
            return Carbon::parse($this->event_date->format('Y-m-d') . ' ' . $this->end_time);
        }

        return $this->closed_at ?: ($this->event_date?->endOfDay());
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->status === 'cancelled') {
            return 'Dibatalkan';
        }

        if ($this->registration_deadline?->isPast() && $this->schedule_start?->isFuture()) {
            return 'Pendaftaran Ditutup';
        }

        if ($this->schedule_start?->isFuture()) {
            return 'Akan Datang';
        }

        if ($this->schedule_end?->isPast() || $this->status === 'completed') {
            return 'Selesai';
        }

        if ($this->schedule_start?->isPast() && $this->schedule_end?->isFuture()) {
            return 'Sedang Berlangsung';
        }

        return match ($this->status) {
            'draft' => 'Draft',
            'ongoing' => 'Sedang Berlangsung',
            default => 'Akan Datang',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status_label) {
            'Sedang Berlangsung' => 'green',
            'Selesai' => 'gray',
            'Dibatalkan' => 'red',
            'Pendaftaran Ditutup' => 'orange',
            'Draft' => 'gray',
            default => 'blue',
        };
    }

    private static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'event';
        $slug = $base;
        $counter = 2;

        while (static::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn (Builder $query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }

    public function scopeActive(Builder $query): void
    {
        $query->whereNotIn('status', ['draft', 'cancelled'])
            ->where(function (Builder $query): void {
                $query->where(function (Builder $query): void {
                    $query->whereNotNull('event_date')
                        ->whereDate('event_date', '>=', today());
                })->orWhere(function (Builder $query): void {
                    $query->whereNull('event_date')
                        ->where('opened_at', '<=', now())
                        ->where('closed_at', '>=', now());
                });
            });
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
