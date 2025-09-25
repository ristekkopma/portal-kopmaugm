<?php

namespace App\Filament\Portal\Resources\PoinAktivitasResource\Pages;

use App\Filament\Portal\Resources\PoinAktivitasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPoinAktivitas extends EditRecord
{
    protected static string $resource = PoinAktivitasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
