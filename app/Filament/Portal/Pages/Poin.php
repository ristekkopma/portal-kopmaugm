<?php

namespace App\Filament\Portal\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Models\PoinBelanja;
use App\Models\PoinAktivitas;

class Poin extends Page
{
    protected static ?string $navigationLabel = 'Poin';
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?int $navigationSort = 5;
    protected static string $view = 'filament.portal.pages.poin';

    public int $totalBelanja;
    public int $totalAktivitas;
    public array $riwayat;
    public string $filterTipe;

    public function mount(): void
    {
        $userId = Auth::id();
        $this->totalBelanja = PoinBelanja::where('user_id', $userId)->sum('total_poin');
        $this->totalAktivitas = PoinAktivitas::where('user_id', $userId)->sum('jumlah_poin');
        $this->filterTipe = request()->query('filterTipe', 'semua');
        $this->riwayat = $this->getRiwayat();
    }

    public function getRiwayat(): array
{
    $userId = Auth::id();

    $belanja = PoinBelanja::where('user_id', $userId)->get()->map(function ($item) {
        return [
            'tanggal' => $item->tanggal_transaksi,
            'poin' => $item->total_poin,
            'keterangan' => $item->usaha,
            'tipe' => 'belanja',
        ];
    });

    $aktivitas = PoinAktivitas::where('user_id', $userId)->get()->map(function ($item) {
        return [
            'tanggal' => $item->tanggal_kegiatan,
            'poin' => $item->jumlah_poin,
            'keterangan' => $item->nama_kegiatan,
            'tipe' => 'aktivitas',
        ];
    });

    $gabung = match ($this->filterTipe) {
        'belanja' => $belanja,
        'aktivitas' => $aktivitas,
        default => $belanja->concat($aktivitas),
    };

    return $gabung
        ->sortByDesc('tanggal')
        ->take(10)
        ->values()
        ->toArray();
}

}