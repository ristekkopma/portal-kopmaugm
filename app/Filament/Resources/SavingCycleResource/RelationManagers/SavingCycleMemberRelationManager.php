<?php

namespace App\Filament\Resources\SavingCycleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Components as AppComponents;
use App\Models\Transaction;
use Filament\Notifications\Notification;

class SavingCycleMemberRelationManager extends RelationManager
{
    protected static string $relationship = 'savingCycleMember';

    public static function getModelLabel(): string
    {
        return __('Saving cycle member');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->preload()
                    ->relationship('user', 'name', fn(Builder $query) => $query->whereDoesntHave('savingCycleMember', fn(Builder $query) => $query->where('saving_cycle_id', $this->getOwnerRecord()->id)))
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user_id')
            ->heading(__('Saving cycle member'))
            ->columns([
                Tables\Columns\TextColumn::make('member.code')
                    ->label('NAK')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('paid_off_at')
                    ->datetime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                AppComponents\Columns\LastModifiedColumn::make(),
                AppComponents\Columns\CreatedAtColumn::make(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\TernaryFilter::make('paid_off_at')
                    ->label('Payment')
                    ->trueLabel(__('Paid'))
                    ->falseLabel(__('Unpaid'))
                    ->nullable(),
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\Action::make('pay')
                    ->label(__('Pay'))
                    ->button()
                    ->icon('heroicon-s-credit-card')
                    ->outlined()
                    ->hidden(fn($record) => $record->paid_off_at)
                    ->requiresConfirmation()
                    ->modalHeading(fn($record) => __('Pay for ') . $record->user->name)
                    ->modalDescription(fn($record) => __('Create new transaction with amount ') . $record->amount)
                    ->action(function ($record) {
                        $transaction = new Transaction;

                        $transaction->wallet_id = $record->user->wallet->id;
                        $transaction->type = true; //debit
                        $transaction->amount = $record->amount;
                        $transaction->reference = $record->savingCycle->reference;
                        $transaction->transacted_at = now();
                        $transaction->note = __('Pay for ') . $record->savingCycle->name;

                        $transaction->save();

                        $record->paid_off_at = now();
                        $record->save();

                        Notification::make()
                            ->success()
                            ->title(__('Saving cycle member has been paid'))
                            ->send();
                    }),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
