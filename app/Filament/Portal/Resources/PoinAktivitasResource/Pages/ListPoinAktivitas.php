<?php

namespace App\Filament\Portal\Resources\PoinAktivitasResource\Pages;

use App\Filament\Portal\Resources\PoinAktivitasResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use App\Models\PoinAktivitas;
use App\Filament\Portal\Resources\PoinAktivitasResource\Widgets\TotalPoinAktivitas;
use Illuminate\Database\Eloquent\Builder;

class ListPoinAktivitas extends ListRecords
{
    protected static string $resource = PoinAktivitasResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            TotalPoinAktivitas::class,
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        return PoinAktivitas::query()
            ->where('user_id', Auth::id())
            ->latest('tanggal_kegiatan');
    }
}
