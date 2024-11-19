<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TransactionReference: string implements HasLabel, HasColor
{
    case Base = 'base';
    case Mandatory = 'mandatory';
    case Voluntary = 'voluntary';

    public function getLabel(): string
    {
        return match ($this) {
            self::Base => __('Base'),
            self::Mandatory => __('Mandatory'),
            self::Voluntary => __('Voluntary'),
        };
    }
    public function getColor(): string
    {
        return match ($this) {
            self::Base => 'success',
            self::Mandatory => 'danger',
            self::Voluntary => 'warning',
        };
    }
}
