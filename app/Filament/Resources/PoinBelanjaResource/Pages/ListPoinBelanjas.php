<?php

namespace App\Filament\Resources\PoinBelanjaResource\Pages;

use App\Filament\Resources\PoinBelanjaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPoinBelanjas extends ListRecords
{
    protected static string $resource = PoinBelanjaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
