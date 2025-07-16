<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use Filament\Resources\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\FileUpload;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TransactionImport;


class ImportTransactions extends Page implements HasForms
{
    use WithFileUploads, InteractsWithForms;

    public static ?string $title = 'Import';

    public $file;

    protected static string $resource = \App\Filament\Resources\TransactionResource::class;
    protected static string $view = 'filament.resources.transaction-resource.pages.import-transactions';

    public function mount(): void
    {
        $this->form->fill(); // penting agar form terinisialisasi
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            FileUpload::make('file')
                ->label('Upload Excel / CSV')
                ->acceptedFileTypes(['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                ->required()
                 ->disk('local') 
                 ->storeFiles(false)
                ->columnSpanFull(),
        ]);
    }

    public function import()
{
    // Ambil semua data dari form
    $data = $this->form->getState();

    // Ambil file dari state form, bukan dari $this->file langsung
    $this->file = $data['file'] ?? null;

    // Validasi manual
    if (!$this->file || !$this->file instanceof \Illuminate\Http\UploadedFile) {
        \Filament\Notifications\Notification::make()
            ->title('Gagal: File tidak ditemukan atau tidak valid.')
            ->danger()
            ->send();
        return;
    }

    try {
        Excel::import(new TransactionImport, $this->file->getRealPath());

        \Filament\Notifications\Notification::make()
            ->title('Berhasil mengimpor transaksi.')
            ->success()
            ->send();
    } catch (\Exception $e) {
        \Filament\Notifications\Notification::make()
            ->title('Terjadi kesalahan saat impor: ' . $e->getMessage())
            ->danger()
            ->send();
    }

//     // Debug dulu kalau perlu
//     // dd($this->file->getClientOriginalName());

//     // TODO: Lakukan proses import Excel di sini
//     // Excel::import(new TransactionImport, $this->file);

//     \Filament\Notifications\Notification::make()
//         ->title('Berhasil mengimpor transaksi.')
//         ->success()
//         ->send();
// }

}
}