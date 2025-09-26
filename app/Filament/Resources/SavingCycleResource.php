<?php

namespace App\Filament\Resources;

use App\Enums\TransactionReference;
use App\Filament\Resources\SavingCycleResource\Pages;
use App\Filament\Resources\SavingCycleResource\RelationManagers;
use App\Filament\Resources\SavingCycleResource\RelationManagers\SavingCycleMemberRelationManager;

use App\Models\SavingCycle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Components as AppComponents;
use Filament\Support\RawJs;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\App;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Concerns\HasRelationManagers;


class SavingCycleResource extends Resource
{
    protected static ?string $model = SavingCycle::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Saving Cycle';
    protected static ?string $navigationGroupShort = '3';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(200),
                        Forms\Components\ToggleButtons::make('reference')
                            ->options(TransactionReference::class)
                            ->default(TransactionReference::Mandatory)
                            ->inline(),
                        Forms\Components\DatePicker::make('start_at'),
                        Forms\Components\DatePicker::make('end_at'),
                        Forms\Components\TextInput::make('default_amount')
                            ->prefix('Rp')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->required()
                            ->required()
                            ->minValue(0)
                            ->step(100),
                    ])->columns(2),
                ])->columnSpan(2),
                Forms\Components\Group::make([
                    AppComponents\Forms\TimestampPlaceholder::make()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                AppComponents\Columns\IDColumn::make(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_at')
                    ->dateTime('d F Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_at')
                    ->dateTime('d F Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('reference')
                    ->badge()
                    ->searchable(),
                AppComponents\Columns\LastModifiedColumn::make(),
                AppComponents\Columns\CreatedAtColumn::make(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // public static function getRelations(): array
    // {
    //     return [
    //         RelationManagers\SavingCycleMemberRelationManager::class
    //     ];
    // }
    public static function getRelations(): array
{
    return [
        SavingCycleMemberRelationManager::class,
    ];
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSavingCycles::route('/'),
            'create' => Pages\CreateSavingCycle::route('/create'),
            'edit' => Pages\EditSavingCycle::route('/{record}/edit'),
        ];
    }
}
