<?php

namespace App\Filament\Resources\LandingContentResource\Pages;

use App\Filament\Resources\LandingContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLandingContents extends ListRecords
{
    protected static string $resource = LandingContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
