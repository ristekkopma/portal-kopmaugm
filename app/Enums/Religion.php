<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Religion: string implements HasLabel
{
    case Islam = 'islam';
    case Christianity = 'christianity';
    case Hinduism = 'hinduism';
    case Buddhism = 'buddhism';
    case Sikhism = 'sikhism';
    case Jainism = 'jainism';
    case Zoroastrianism = 'zoroastrianism';
    case Taoism = 'taoism';
    case Other = 'other';

    public function getLabel(): ?string
    {
        return __($this->name);
    }
}
