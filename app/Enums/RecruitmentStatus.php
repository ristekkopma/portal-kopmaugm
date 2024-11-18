<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum RecruitmentStatus: string implements HasLabel, HasColor
{
    case Submitted = 'submitted';
    case Scheduled = 'Scheduled';
    case Interviewed = 'interviewed';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::Submitted => 'Submitted',
            self::Scheduled => 'Scheduled',
            self::Interviewed => 'Interviewed',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Submitted => 'gray',
            self::Scheduled => 'primary',
            self::Interviewed => 'warning',
            self::Approved => 'success',
            self::Rejected => 'danger',
        };
    }
}
