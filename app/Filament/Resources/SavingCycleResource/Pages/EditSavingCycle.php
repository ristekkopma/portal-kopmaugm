<?php

namespace App\Filament\Resources\SavingCycleResource\Pages;

use App\Filament\Resources\SavingCycleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
 use HasRelationManagers;

class EditSavingCycle extends EditRecord
{
    protected static string $resource = SavingCycleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

}
