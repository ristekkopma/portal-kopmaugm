<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Hash;
use App\Models\Transaction;
use Livewire\WithFileUploads;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TransactionImport;


class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
           
        Actions\CreateAction::make(),

        Action::make('import')
                ->label('Import')
                ->icon('heroicon-m-arrow-up-tray')
                ->modalHeading('Import Data Transaksi')
                ->form([
                    FileUpload::make('file')
                        ->label('Upload CSV')
                        ->acceptedFileTypes(['text/csv'])
                        ->required()
                        ->disk('local')
                        ->storeFiles(false),
                ])
                ->action(function (array $data): void {
                    try {
                        $import = new TransactionsImport;
                        Excel::import($import, $data['file'], null, \Maatwebsite\Excel\Excel::CSV, [
                        'withoutTransaction' => true,]);

                        $notif = Notification::make()
                            ->title('Berhasil mengimpor data pengguna.');

                        if ($import->duplicateCount > 0) {
                            $notif->body("{$import->duplicateCount} data diabaikan karena duplikat.");
                        }

                        $notif->success()->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal mengimpor')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->modalSubmitActionLabel('Import')
                ->color('primary'),
        ];
    }
}
