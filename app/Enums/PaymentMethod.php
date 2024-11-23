<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PaymentMethod: string implements HasLabel, HasColor
{
    case Cash = 'cash';
    case Transfer = 'transfer';

    public function getLabel(): string
    {
        return match ($this) {
            self::Cash => __('Cash'),
            self::Transfer => __('Transfer'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Cash => 'gray',
            self::Transfer => 'success',
        };
    }
}
