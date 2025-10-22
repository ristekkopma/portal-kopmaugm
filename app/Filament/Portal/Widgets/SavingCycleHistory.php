<?php

namespace App\Filament\Portal\Widgets;

use App\Enums\UserRole;
use App\Models\Member;
use App\Models\SavingCycleMember;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;


class SavingCycleHistory extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getTableHeading(): string
    {
        return __('Payment history');
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(
                SavingCycleMember::query()->whereBelongsTo(Auth::user())->orderBy('created_at', 'desc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('savingCycle.name'),
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('paid_off_at')
                    ->label(__('Status'))
                    ->formatStateUsing(fn($state) => $state !== null ? __('Paid') : __('Unpaid'))
                    ->badge()
                    ->placeholder(__('Unpaid'))
                    ->description(fn($state) => $state ? $state->format('d F Y H:i') : null),
            ])
            ->paginated([3, 5, 10, 20])
            ->defaultPaginationPageOption(3);
    }

    public static function canView(): bool
    {
        return Auth::user()->role !== UserRole::Candidate;

    }
}
