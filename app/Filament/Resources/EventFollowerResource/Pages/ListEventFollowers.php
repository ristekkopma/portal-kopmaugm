<?php

namespace App\Filament\Resources\EventFollowerResource\Pages;

use App\Filament\Resources\EventFollowerResource;
use App\Filament\Widgets\EventFollowerStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventFollowers extends ListRecords
{
    protected static string $resource = EventFollowerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn (): string => route('admin.event-followers.export', [
                    'event_id' => data_get($this->tableFilters, 'event_id.value'),
                    'status' => data_get($this->tableFilters, 'status.value'),
                ]))
                ->visible(fn (): bool => auth()->user()->can('export_event_followers')),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [EventFollowerStats::class];
    }
}
