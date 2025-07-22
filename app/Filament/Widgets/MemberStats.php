<?php

namespace App\Filament\Widgets;

use App\Enums\UserRole;
use App\Models\Member;
use App\Models\SavingCycle;
use App\Models\SavingCycleMember;
use App\Models\Wallet;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Facades\Filament;

class MemberStats extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        
        return [
            
            Stat::make(__('Total members'), Member::member()->count() . ' ' . __('Person'))
                ->description(__('Candidate') . ' ' . Member::candidate()->count() . ' ' . __('Person'))
                ->color('primary')
                ->icon('heroicon-o-users')
                ->url(route('filament.admin.resources.members.index'))
                ->chart(array_map(fn() => rand(1, 100), range(1, 7))),

            Stat::make(__('Balance all wallets'), 'Rp ' . number_format(Wallet::sum('balance'), 0, ',', '.'))
                ->description(__('From') . ' ' . Wallet::count() . ' ' . __('Wallet'))
                ->color('success')
                ->icon('heroicon-o-wallet')
                ->url(route('filament.admin.resources.wallets.index'))
                ->chart(array_map(fn() => rand(1, 100), range(1, 7))),

            Stat::make(__('Unpaid saving cycles'), 'Rp ' . number_format(
                SavingCycleMember::whereNull('paid_off_at')->sum('amount'), 0, ',', '.'))
                ->description(__('From') . ' ' . SavingCycleMember::whereNull('paid_off_at')->count() . ' ' . __('arrears'))
                ->color('danger')
                ->icon('heroicon-s-credit-card')
                ->url(route('filament.admin.resources.saving-cycles.index'))
                ->chart(array_map(fn() => rand(1, 100), range(1, 7))),
        ];
    }

    public static function canView(): bool
    {
         return Filament::getCurrentPanel()?->getId() === 'admin'
        && auth()->user()->role !== UserRole::Candidate
        && auth()->user()->role !== UserRole::Member;
    }

    public function redirectToIndexMember()
    {
        return redirect()->route('filament.admin.resources.members.index');
    }
    public function redirectToIndexWallet()
    {

        return redirect()->route('filament.admin.resources.wallets.index');
    }
    public function redirectToIndexSavingCycle()
    {
        return redirect()->route('filament.admin.resources.saving-cycles.index');
    }
}
