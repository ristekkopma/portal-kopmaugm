<?php

namespace App\Filament\Portal\Resources;

use App\Filament\Portal\Resources\PoinBelanjaResource\Pages;
use App\Filament\Portal\Resources\PoinBelanjaResource\RelationManagers;
use App\Models\PoinBelanja;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PoinBelanjaResource extends Resource
{
    protected static ?string $model = PoinBelanja::class;
    protected static ?string $navigationGroup = 'Poin';
    protected static ?string $navigationLabel = 'Poin Shopping';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                // form khusus superadmin
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal_transaksi')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('usaha')
                    ->label('Usaha')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nominal')
                    ->label('Nominal')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_poin')
                    ->label('Poin')
                    ->sortable(),
            ])
            ->defaultSort('tanggal_transaksi', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPoinBelanja::route('/'),
            // 'create' => Pages\CreatePoinBelanja::route('/create'),
            // 'edit' => Pages\EditPoinBelanja::route('/{record}/edit'),
        ];
    }
}