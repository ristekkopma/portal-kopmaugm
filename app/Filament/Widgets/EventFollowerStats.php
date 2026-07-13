<?php

namespace App\Filament\Widgets;

use App\Models\EventFollower;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class EventFollowerStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $eventId = data_get(request()->input('tableFilters'), 'event_id.value');
        $query = EventFollower::query()
            ->when($eventId, fn (Builder $query) => $query->where('event_id', $eventId));

        return [
            Stat::make('Total Peminat', (clone $query)->where('status', '!=', 'cancelled')->count())
                ->icon('heroicon-o-star'),
            Stat::make('Total Terdaftar', (clone $query)->where('status', 'registered')->count())
                ->color('info')->icon('heroicon-o-clipboard-document-check'),
            Stat::make('Total Hadir', (clone $query)->where('status', 'attended')->count())
                ->color('success')->icon('heroicon-o-check-badge'),
            Stat::make('Total Membatalkan', (clone $query)->where('status', 'cancelled')->count())
                ->color('danger')->icon('heroicon-o-x-circle'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()?->can('view_event_followers') ?? false;
    }
}
