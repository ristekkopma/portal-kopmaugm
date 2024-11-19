<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use App\Models\SavingCycleMember;
use App\Models\Transaction;
use Closure;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageSavingCycleMembers extends ManageRelatedRecords
{
    protected static string $resource = MemberResource::class;

    protected static string $relationship = 'savingCycleMembers';

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';

    public static function getNavigationLabel(): string
    {
        return __('Saving cycle');
    }

    public function getTitle(): string
    {
        return __('Saving cycle') . ' - ' . $this->record->user->name;
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
            ->checkIfRecordIsSelectableUsing(fn(SavingCycleMember $record) => is_null($record->paid_off_at))
            ->recordTitleAttribute('savingCycle.name')
            ->columns([
                Tables\Columns\TextColumn::make('savingCycle.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('paid_off_at'),
            ])
            ->actions([
                Tables\Actions\Action::make('pay')
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('pay')
                        ->label('Bulk payment')
                        ->icon('heroicon-s-credit-card')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->modalHeading(fn(Collection $records) => __('Bulk payment for') . $records->first()->user->name)
                        ->modalDescription(fn(Collection $records) => __('Create new transaction with amount ') . $records->sum('amount'))
                        ->action(function (Collection $records) {
                            $transaction = new Transaction;

                            $transaction->wallet_id = $records->first()->user->wallet->id;
                            $transaction->type = true; // debit
                            $transaction->amount = $records->sum('amount');
                            $transaction->reference = $records->first()->savingCycle->reference;
                            $transaction->transacted_at = now();
                            $transaction->note = __('Pay for ') . $records->first()->savingCycle->reference . ' ' . $records->pluck('savingCycle.name')->join(', ');

                            $transaction->save();

                            // Tandai setiap record sebagai sudah dibayar
                            $records->each(function (SavingCycleMember $record) {
                                $record->paid_off_at = now();
                                $record->save();
                            });

                            Notification::make()
                                ->success()
                                ->title(__('Saving cycle member has been paid'))
                                ->send();
                        })
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
