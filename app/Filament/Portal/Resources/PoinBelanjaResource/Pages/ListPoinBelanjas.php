<?php

namespace App\Filament\Portal\Resources\PoinBelanjaResource\Pages;

use App\Filament\Portal\Resources\PoinBelanjaResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use App\Models\PoinBelanja;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Portal\Resources\PoinBelanjaResource\Pages\Card;
use App\Filament\Portal\Resources\PoinBelanjaResource\Widgets\TotalPoinBelanja;


class ListPoinBelanjas extends ListRecords
{
    protected static string $resource = PoinBelanjaResource::class;

    protected function getHeaderWidgets(): array
{
    return [
         TotalPoinBelanja::class,
    ];
}


    protected function getTableQuery(): ?Builder
    {
        $userId = Auth::id();

        return PoinBelanja::query()
            ->where('user_id', $userId)
            ->latest('tanggal_transaksi')
            ->limit(10);
    }
}
