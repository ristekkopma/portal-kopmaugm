<?php

namespace App\Filament\Portal\Resources;

use App\Filament\Portal\Resources\PoinAktivitasResource\Pages;
use App\Filament\Portal\Resources\PoinAktivitasResource\RelationManagers;
use App\Models\PoinAktivitas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PoinAktivitasResource extends Resource
{
    protected static ?string $model = PoinAktivitas::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
  
    protected static ?string $navigationGroup = 'Poin';

    protected static ?string $navigationLabel = 'Poin Activity';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('nama_kegiatan')->label('Kegiatan'),
            Tables\Columns\TextColumn::make('jumlah_poin')->label('Poin'),
            Tables\Columns\TextColumn::make('tanggal_kegiatan')->date()->label('Tanggal'),
        ])
        ->actions([])
        ->bulkActions([]);
}

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPoinAktivitas::route('/'),
           
        ];
    }
}
