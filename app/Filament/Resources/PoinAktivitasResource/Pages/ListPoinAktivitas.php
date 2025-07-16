<?php

namespace App\Filament\Resources\PoinAktivitasResource\Pages;

use App\Filament\Resources\PoinAktivitasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PoinAktivitasImport;


class ListPoinAktivitas extends ListRecords
{
    protected static string $resource = PoinAktivitasResource::class;

    protected function getHeaderActions(): array
{
    return [
        Actions\CreateAction::make(),

        Action::make('import')
            ->label('Import')
            ->icon('heroicon-o-arrow-down-tray')
            ->modalHeading('Import Poin Aktivitas (CSV)')
            ->form([
                FileUpload::make('file')
                    ->label('Upload CSV')
                    ->acceptedFileTypes(['text/csv'])
                    ->required()
                    ->disk('local')
                    ->storeFiles(false),
            ])
            ->action(function (array $data) {
                try {
                    Excel::import(new PoinAktivitasImport, $data['file']);

                     $notif = \Filament\Notifications\Notification::make()
            ->title('Berhasil mengimpor data');

        if ($import->hasDuplicates) {
            $notif->body('Beberapa data diabaikan karena terindikasi duplikat.');
        }
                } catch (\Exception $e) {
                    \Filament\Notifications\Notification::make()
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
