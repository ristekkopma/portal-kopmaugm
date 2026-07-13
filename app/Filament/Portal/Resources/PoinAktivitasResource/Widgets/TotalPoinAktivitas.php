<?php

namespace App\Filament\Portal\Resources\PoinAktivitasResource\Widgets;

use App\Models\PoinAktivitas;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class TotalPoinAktivitas extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(
                'Total Poin Aktivitas',
                number_format(PoinAktivitas::where('user_id', Auth::id())->sum('jumlah_poin'), 0, ',', '.'),
            )
                ->icon('heroicon-o-sparkles')
                ->color('success'),
        ];
    }
}
