<?php

namespace App\Filament\Portal\Resources\TransactionResource\Pages;

use App\Filament\Portal\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;
}
