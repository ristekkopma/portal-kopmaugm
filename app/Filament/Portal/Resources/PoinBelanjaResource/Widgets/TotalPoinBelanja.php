<?php

namespace App\Filament\Portal\Resources\PoinBelanjaResource\Widgets;

use App\Models\PoinBelanja;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class TotalPoinBelanja extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(
                'Total Poin Belanja',
                number_format(PoinBelanja::where('user_id', Auth::id())->sum('total_poin'), 0, ',', '.'),
            )
                ->icon('heroicon-o-shopping-bag')
                ->color('success'),
        ];
    }
}
