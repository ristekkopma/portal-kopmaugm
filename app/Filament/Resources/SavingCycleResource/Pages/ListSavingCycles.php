<?php

namespace App\Filament\Resources\SavingCycleResource\Pages;

use App\Filament\Resources\SavingCycleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSavingCycles extends ListRecords
{
    protected static string $resource = SavingCycleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
