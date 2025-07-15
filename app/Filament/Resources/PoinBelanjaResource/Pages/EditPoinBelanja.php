<?php

namespace App\Filament\Resources\PoinBelanjaResource\Pages;

use App\Filament\Resources\PoinBelanjaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPoinBelanja extends EditRecord
{
    protected static string $resource = PoinBelanjaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
