<?php

namespace App\Filament\Portal\Widgets;

use App\Enums\UserRole;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class MembershipStats extends BaseWidget
{
    protected static ?int $sort = 4;

    protected static ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        return [
            Stat::make(__('Balance'), 'Rp ' . number_format(Auth::user()->wallet->balance, 0, ',', '.'))
                ->icon('heroicon-o-banknotes')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            Stat::make(__('Unpaid saving cycle'), Auth::user()->member->savingCycleMembers->whereNull('paid_off_at')->count())
                ->description(__('From') . ' ' . Auth::user()->member->savingCycleMembers->count() . ' ' . __('Saving cycle'))
                ->icon('heroicon-o-arrow-path-rounded-square')
                ->color('danger')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            Stat::make(__('Joined at'), Auth::user()?->member?->joined_at->format('d F Y'))
                ->description(Auth::user()?->member?->joined_at->diffForHumans())
                ->icon('heroicon-o-user')
                ->color('warning')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->role !== UserRole::Candidate;
    }
}
