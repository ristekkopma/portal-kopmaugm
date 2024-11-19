<?php

namespace App\Filament\Portal\Widgets;

use App\Enums\UserRole;
use App\Models\Event;
use Filament\Widgets\Widget;

class NextEvent extends Widget
{
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    protected static string $view = 'filament.portal.widgets.next-event';

    public $events;

    public function mount(): void
    {
        $this->events = Event::active()->get();
    }

    public static function canView(): bool
    {
        return auth()->user()->role !== UserRole::Candidate;
    }
}
