<?php

namespace App\Filament\Portal\Widgets;

use App\Enums\UserRole;
use App\Models\Event;
use Filament\Widgets\Widget;

class CandidateWelcome extends Widget
{
    protected static ?int $sort = 0;

    protected int | string | array $columnSpan = 'full';

    protected static string $view = 'filament.portal.widgets.candidate-welcome';

    public static function canView(): bool
    {
        return auth()->user()->role === UserRole::Candidate;
    }
}
