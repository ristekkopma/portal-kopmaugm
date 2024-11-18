<?php

namespace App\Filament\Resources;

use App\Enums\TransactionReference;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Components as AppComponents;
use Illuminate\Support\Facades\App;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationGroup = 'Finance';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\Select::make('wallet_id')
                            ->relationship('wallet', 'id')
                            ->getOptionLabelFromRecordUsing(function ($record) {
                                return $record->user->name;
                            })
                            ->preload(),
                        Forms\Components\ToggleButtons::make('type')
                            ->boolean('Debit', 'Credit')
                            ->icons(['heroicon-o-minus', 'heroicon-o-plus'])
                            ->inline()
                            ->required(),
                    ])->columns(2),
                    Forms\Components\Section::make([
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->step(100)
                            ->prefix('Rp'),
                        Forms\Components\Select::make('reference')
                            ->options(TransactionReference::class)
                            ->preload(),
                    ])->columns(2),
                    Forms\Components\Section::make([
                        Forms\Components\Textarea::make('note')
                            ->columnSpanFull(),
                    ])
                ])->columnSpan(2),
                Forms\Components\Group::make([
                    AppComponents\Forms\TimestampPlaceholder::make(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                AppComponents\Columns\IDColumn::make(),
                Tables\Columns\TextColumn::make('wallet.user.name')
                    ->label('Wallet name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        '0' => 'Credit',
                        '1' => 'Debit',
                    })
                    ->badge(),
                Tables\Columns\TextColumn::make('amount')
                    ->prefix('Rp ')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reference')
                    ->badge()
                    ->searchable(),
                AppComponents\Columns\LastModifiedColumn::make(),
                AppComponents\Columns\CreatedAtColumn::make()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
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
