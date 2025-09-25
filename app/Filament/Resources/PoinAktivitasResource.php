<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PoinAktivitasResource\Pages;
use App\Filament\Resources\PoinAktivitasResource\RelationManagers;
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

    protected static ?int $navigationGroupSort = 4;
    
    public static function getModelLabel(): string
    {
        return __('Poin Activity');
    }

    
   
    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\Select::make('user_id')
                            ->label('Anggota')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('nama_kegiatan')
                            ->label('Nama Kegiatan')
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal_kegiatan')
                            ->label('Tanggal Kegiatan')
                            ->required(),
                        Forms\Components\TextInput::make('jumlah_poin')
                            ->label('Poin')
                            ->numeric()
                            ->required(),
                    ])->columns(2),
                ])->columnSpan(3),

                // Forms\Components\Group::make([
                //     Forms\Components\Placeholder::make('info')
                //         ->label('Catatan')
                //         ->content('Poin dimasukkan manual berdasarkan kegiatan yang diikuti.'),
                // ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('tanggal_kegiatan', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Anggota')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_kegiatan')
                    ->label('Kegiatan'),
                Tables\Columns\TextColumn::make('jumlah_poin')
                    ->label('Poin')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_kegiatan')
                    ->label('Tanggal')
                    ->date(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->since(),
            ])
            ->filters([
                
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->label(false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPoinAktivitas::route('/'),
            'create' => Pages\CreatePoinAktivitas::route('/create'),
            'edit' => Pages\EditPoinAktivitas::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}