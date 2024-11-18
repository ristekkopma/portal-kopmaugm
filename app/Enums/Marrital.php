<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Marrital: string implements HasLabel
{
    case Single = 'single';
    case Married = 'married';
    case Widowed = 'widowed';
    case Divorced = 'divorced';
    case Separated = 'separated';

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
