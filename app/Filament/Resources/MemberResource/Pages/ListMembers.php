<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Imports\MemberImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;



class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Action::make('import')
    ->label('Import')
    ->icon('heroicon-m-arrow-up-tray')
    ->modalHeading('Import Data Anggota')
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
                    $import = new MemberImport;
                    Excel::import($import, $data['file']);

                    $notif = Notification::make()
                        ->title('Berhasil mengimpor anggota.');

                    if ($import->hasDuplicates) {
                        $notif->body('Beberapa data diabaikan karena duplikat.');
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
            ->color('primary')
                ];
            }
}
