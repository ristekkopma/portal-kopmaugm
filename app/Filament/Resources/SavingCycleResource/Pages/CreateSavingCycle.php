<?php

namespace App\Filament\Resources\SavingCycleResource\Pages;

use App\Filament\Resources\SavingCycleResource;
use App\Models\SavingCycleUser;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSavingCycle extends CreateRecord
{
    protected static string $resource = SavingCycleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
