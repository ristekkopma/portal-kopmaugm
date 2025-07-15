<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use Filament\Resources\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Livewire\WithFileUploads;

class ImportTransactions extends Page
{
    use WithFileUploads;

    public static ?string $title = 'Import Transactions';

    public $file;

    protected static string $resource = \App\Filament\Resources\TransactionResource::class;
    protected static string $view = 'filament.resources.transaction-resource.pages.import-transactions';

    public function form(Form $form): Form
    {
        return $form->schema([
            FileUpload::make('file')
                ->label('Upload Excel / CSV')
                ->acceptedFileTypes(['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                ->required()
                ->columnSpanFull(),
        ]);
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|file|mimes:csv,xlsx',
        ]);

        // Excel::import(...);
    }
}
