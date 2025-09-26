<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PoinBelanjaResource\Pages;
use App\Filament\Resources\PoinBelanjaResource\RelationManagers;
use App\Models\PoinBelanja;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Filament\Components as AppComponents;

class PoinBelanjaResource extends Resource
{
    protected static ?string $model = PoinBelanja::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Poin';
    protected static ?string $navigationLabel = 'Poin Shopping';
    protected static ?string $navigationGroupShort = '5';
      


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
                        Forms\Components\TextInput::make('nominal')
                            ->label('Nominal Belanja (Rp)')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => 
                                $set('total_poin', floor($state / 10000))),
                        Forms\Components\TextInput::make('usaha')
                            ->label('Usaha Tempat Belanja')
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal_transaksi')
                            ->label('Tanggal Transaksi')
                            ->required(),
                        Forms\Components\TextInput::make('total_poin')
                            ->label('Poin')
                            ->numeric()
                            ->readOnly()
                            ->required(), // opsional, tergantung

                    ])->columns(2),
                ])->columnSpan(3),

                // Forms\Components\Group::make([
                //     Forms\Components\Placeholder::make('total_poin')
                //         ->label('Poin')
                //         ->content('Akan dihitung otomatis: 1 poin per Rp10.000'),
                // ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('tanggal_transaksi', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Anggota')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nominal')
                    ->label('Nominal (Rp)')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('total_poin')
                    ->label('Poin')
                    ->sortable(),
                Tables\Columns\TextColumn::make('usaha')
                    ->label('Tempat Belanja'),
                Tables\Columns\TextColumn::make('tanggal_transaksi')
                    ->label('Tanggal')
                    ->date(),
            ])
            // ->filters([
            //     Tables\Filters\SelectFilter::make('user.name')
            //         ->label('Owner')
            //         ->searchable()
            //         ->getOptionLabelFromRecordUsing(fn($record) => $record->user->name),
                

            // ])
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPoinBelanjas::route('/'),
            'create' => Pages\CreatePoinBelanja::route('/create'),
            'edit' => Pages\EditPoinBelanja::route('/{record}/edit'),
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