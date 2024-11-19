<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MemberType: string implements HasLabel
{
    case Regular = 'regular';
    case ExtraOrdinary = 'extra_ordinary';

    public function getLabel(): string
    {
        return match ($this) {
            self::Regular => __('Regular'),
            self::ExtraOrdinary => __('Extra ordinary'),
        };
    }
}
