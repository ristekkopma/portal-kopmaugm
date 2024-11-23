<?php

namespace App\Filament\Portal\Resources\DocumentResource\Pages;

use App\Filament\Portal\Resources\DocumentResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            __('Kopma document') => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->whereNull('member_id')),
            __('Member document') => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->whereNotNull('member_id')->whereBelongsTo(auth()->user()->member)),
        ];
    }
}
