<?php

namespace App\Enums;

enum MemberStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';

    public static function options(): array
    {
        return [
            self::Active->value => 'Active',
            self::Inactive->value => 'Inactive',
        ];
    }
}

