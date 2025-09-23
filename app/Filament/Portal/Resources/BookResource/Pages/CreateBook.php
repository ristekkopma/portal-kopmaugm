<?php

namespace App\Filament\Portal\Resources\BookResource\Pages;

use App\Filament\Portal\Resources\BookResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBook extends CreateRecord
{
    protected static string $resource = BookResource::class;
}
