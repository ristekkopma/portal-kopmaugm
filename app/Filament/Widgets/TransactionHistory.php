<?php

namespace App\Filament\Widgets;

use App\Enums\UserRole;
use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TransactionHistory extends ChartWidget
{
    public function getHeading(): string
    {
        return __('Transaction history chart');
    }

    protected function getData(): array
    {
        $credit = Trend::model(Transaction::class)
            ->query(Transaction::where('type', false))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        $debit = Trend::model(Transaction::class)
            ->query(Transaction::where('type', true))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => __('Credit'),
                    'backgroundColor' => '#d97706',
                    'borderColor' => '#d97706',
                    'data' => $credit->map(fn(TrendValue $value) => $value->aggregate),
                ],
                [
                    'label' => __('Debit'),
                    'backgroundColor' => '#0284c7',
                    'borderColor' => '#0284c7',
                    'data' => $debit->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $credit->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public static function canView(): bool
    {
        return auth()->user()->role !== UserRole::Candidate && auth()->user()->role !== UserRole::Member;
    }
}
