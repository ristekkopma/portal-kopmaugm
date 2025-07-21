<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Livewire\WithFileUploads;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UserImport;

class ListUsers extends ListRecords
{
    use WithFileUploads;

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Action::make('import')
                ->label('Import')
                ->icon('heroicon-m-arrow-up-tray')
                ->modalHeading('Import Data Pengguna')
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
                        $import = new UserImport;
                        Excel::import($import, $data['file']);

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
