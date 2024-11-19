<?php

namespace App\Filament\Portal\Widgets;

use App\Enums\UserRole;
use Filament\Widgets\Widget;

class Ekmc extends Widget
{
    protected static ?int $sort = 1;

    protected static string $view = 'filament.portal.widgets.ekmc';

    public static function canView(): bool
    {
        return auth()->user()->role !== UserRole::Candidate;
    }
}
