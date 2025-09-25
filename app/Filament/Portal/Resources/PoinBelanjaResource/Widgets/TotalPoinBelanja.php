<?php

namespace App\Filament\Portal\Resources\PoinBelanjaResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;       // <- wajib untuk Auth::id()
use App\Models\PoinBelanja;               // <- wajib untuk model PoinBelanja

class TotalPoinBelanja extends Widget
{
    protected static string $view = 'filament.portal.resources.poin-belanja-resource.widgets.total-poin-belanja';

    protected int | string | array $columnSpan = 'full';

    public int $total = 0;

    public function mount(): void
    {
        $this->total = PoinBelanja::where('user_id', Auth::id())->sum('total_poin');
    }
}
