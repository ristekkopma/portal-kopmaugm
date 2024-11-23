<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageTransactions extends ManageRelatedRecords
{
    protected static string $resource = MemberResource::class;

    protected static string $relationship = 'user';

    public function getRelationship(): Relation | Builder
    {
        return $this->getOwnerRecord()->user->wallet->transactions();
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return __('Transaction');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('amount')
            ->columns([
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
                    ->sortable()
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('reference')
                    ->badge(),
                Tables\Columns\TextColumn::make('transacted_at')
                    ->sortable()
                    ->searchable()
                    ->dateTime('d F Y H:i'),
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
