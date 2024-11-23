<?php

namespace App\Filament\Portal\Resources;

use App\Enums\TransactionReference;
use App\Filament\Portal\Resources\TransactionResource\Pages;
use App\Filament\Portal\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Components as AppComponents;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\RawJs;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    public static function getModelLabel(): string
    {
        return __('Transaction');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\Select::make('wallet_id')
                            ->label('Name')
                            ->relationship('wallet', 'id', fn(Builder $query) => $query->orderBy('created_at', 'desc'))
                            ->getOptionLabelFromRecordUsing(function ($record) {
                                return $record->user->name;
                            })
                            ->preload(),
                        Forms\Components\ToggleButtons::make('type')
                            ->boolean(__('Debit'), __('Credit'))
                            ->icons(['heroicon-o-minus', 'heroicon-o-plus'])
                            ->inline()
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->step(100)
                            ->prefix('Rp'),
                        Forms\Components\ToggleButtons::make('reference')
                            ->options(TransactionReference::class)
                            ->inline()
                            ->required(),
                    ])->columns(2),
                    Forms\Components\Section::make([
                        Forms\Components\Textarea::make('note')
                            ->columnSpanFull(),
                    ])
                ])->columnSpan(2),
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\DateTimePicker::make('transacted_at')
                            ->dehydrateStateUsing(fn($state): string => $state !== null ? $state : now())
                    ]),
                    AppComponents\Forms\TimestampPlaceholder::make(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('transacted_at')
                    ->datetime('d F Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('wallet.user.name')
                    ->label('Wallet')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('type')
                    ->formatStateUsing(fn(bool $state): string => match ($state) {
                        true => __('Debit'),
                        false => __('Credit'),
                    })
                    ->color(fn(bool $state): string => match ($state) {
                        true => 'success',
                        false => 'warning'
                    })
                    ->badge(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('reference')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->badge(),
                AppComponents\Columns\LastModifiedColumn::make(),
                AppComponents\Columns\CreatedAtColumn::make(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->modalWidth(MaxWidth::ThreeExtraLarge),
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
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereBelongsTo(auth()->user()->wallet)
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
