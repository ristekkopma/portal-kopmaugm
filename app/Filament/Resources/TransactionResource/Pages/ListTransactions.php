<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Actions\Action;


class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
           Action::make('Import')
            ->url(TransactionResource::getUrl('import'))
            ->icon('heroicon-o-arrow-down-tray'),
        Actions\CreateAction::make(),
    ];
    }
}
