<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasLabel, HasColor
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case Member = 'member';
    case Candidate = 'candidate';
    case Finance = 'finance';

    public function getLabel(): string
    {
        return match ($this) {
            self::SuperAdmin => __('Super Admin'),
            self::Admin => __('Admin'),
            self::Member => __('Member'),
            self::Candidate => __('Candidate'),
            self::Finance => __('Finance'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::SuperAdmin => 'success',
            self::Admin => 'primary',
            self::Member => 'gray',
            self::Candidate => 'danger',
            self::Finance => 'warning',
        };
    }
}
