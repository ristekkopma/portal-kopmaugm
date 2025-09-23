<?php

namespace App\Filament\Portal\Resources\BorrowingResource\Pages;

use App\Filament\Portal\Resources\BorrowingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBorrowing extends EditRecord
{
    protected static string $resource = BorrowingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
