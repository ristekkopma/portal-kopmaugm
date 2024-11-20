<?php

namespace App\Filament\Widgets;

use App\Enums\UserRole;
use App\Models\Member;
use App\Models\SavingCycle;
use App\Models\SavingCycleMember;
use App\Models\Wallet;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MemberStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(__('Total member'), Member::member()->count() . ' ' . __('Person'))
                ->description(__('Candidate') . ' ' . Member::candidate()->count() . ' ' . __('Person'))
                ->color('primary')
                ->icon('heroicon-o-users')
                ->chart(array_map(fn() => rand(1, 100), range(1, 7))),
            Stat::make(__('Balance all wallet'), 'Rp ' . number_format(Wallet::sum('balance'), 0, ',', '.'))
                ->description(__('From') . ' ' . Wallet::count() . ' ' . __('Wallet'))
                ->color('success')
                ->icon('heroicon-o-wallet')
                ->chart(array_map(fn() => rand(1, 100), range(1, 7))),
            Stat::make(__('Unpaid saving cycle'), 'Rp ' . number_format(SavingCycleMember::whereNull('paid_off_at')->sum('amount'), 0, ',', '.'))
                ->description(__('From') . ' ' . SavingCycleMember::whereNull('paid_off_at')->count() . ' ' . __('arrears'))
                ->color('danger')
                ->icon('heroicon-s-credit-card')
                ->chart(array_map(fn() => rand(1, 100), range(1, 7))),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->role !== UserRole::Candidate && auth()->user()->role !== UserRole::Member;
    }
}
