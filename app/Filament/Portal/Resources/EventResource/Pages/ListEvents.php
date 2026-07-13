<?php

namespace App\Filament\Portal\Resources\EventResource\Pages;

use App\Enums\UserRole;
use App\Filament\Portal\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    public function getHeading(): string
    {
        return 'Events';
    }

    public function getSubheading(): ?string
    {
        return 'Temukan dan ikuti agenda terbaru Kopma UGM.';
    }

    public function getBreadcrumbs(): array
    {
        return [EventResource::getUrl() => 'Events', 'List'];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('manage')
                ->label('Kelola Event')
                ->icon('heroicon-o-cog-6-tooth')
                ->url(route('filament.admin.resources.events.index'))
                ->visible(fn (): bool => in_array(auth()->user()->role, [
                    UserRole::SuperAdmin,
                    UserRole::Admin,
                ], true)),
        ];
    }
}
