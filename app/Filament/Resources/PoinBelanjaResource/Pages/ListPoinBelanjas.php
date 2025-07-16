<?php

namespace App\Filament\Resources\PoinBelanjaResource\Pages;

use App\Filament\Resources\PoinBelanjaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PoinBelanjaImport;
use Filament\Notifications\Notification;


class ListPoinBelanjas extends ListRecords
{
    protected static string $resource = PoinBelanjaResource::class;
    

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),


            Action::make('import')
            ->label('Import')
            ->icon('heroicon-o-arrow-down-tray')

            ->modalHeading('Import Poin Belanja')
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
                    Excel::import(new PoinBelanjaImport, $data['file']);

                    // Reset counter
         $import = new PoinBelanjaImport;
        Excel::import($import, $data['file']);

        $notif = \Filament\Notifications\Notification::make()
            ->title('Berhasil mengimpor data');

        if ($import->hasDuplicates) {
            $notif->body('Beberapa data diabaikan karena terindikasi duplikat.');
        }

        $notif->success()->send();
                } catch (\Exception $e) {
                    \Filament\Notifications\Notification::make()
                        ->title('Gagal mengimpor')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            })
            ->modalSubmitActionLabel('Import')
            ->color('primary')

            

    
        ];
    }
}
