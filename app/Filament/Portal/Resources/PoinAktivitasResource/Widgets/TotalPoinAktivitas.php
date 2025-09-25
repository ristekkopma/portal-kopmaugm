<?php

namespace App\Filament\Portal\Resources\PoinAktivitasResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use App\Models\PoinAktivitas;

class TotalPoinAktivitas extends Widget
{
    protected static string $view = 'filament.portal.resources.poin-aktivitas-resource.widgets.total-poin-aktivitas';

    public int $total = 0;

    // Widget mengambil seluruh kolom grid
    protected int | string | array $columnSpan = 'full';

    public function mount(): void
    {
        $this->total = PoinAktivitas::where('user_id', Auth::id())->sum('jumlah_poin');
    }
}
