<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventFollowerResource\Pages;
use App\Models\EventFollower;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class EventFollowerResource extends Resource
{
    protected static ?string $model = EventFollower::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Manajemen Event';

    protected static ?string $navigationLabel = 'Peminat Event';

    protected static ?string $modelLabel = 'Peminat Event';

    protected static ?string $pluralModelLabel = 'Peminat Event';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('status')
                ->label('Status Keikutsertaan')
                ->options(EventFollower::statuses())
                ->required(),
            Forms\Components\Textarea::make('notes')
                ->label('Catatan Admin')
                ->rows(4)
                ->maxLength(2000),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Pengguna')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('user.phone')
                    ->label('Nomor Telepon')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('user.profile.instance')
                    ->label('Instansi/Divisi')
                    ->placeholder('-')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('event.title')
                    ->label('Nama Event')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('event.event_date')
                    ->label('Tanggal Event')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => EventFollower::statuses()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'registered' => 'info',
                        'attended' => 'success',
                        'cancelled' => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(40)
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Menyatakan Minat')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event_id')
                    ->label('Event')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(EventFollower::statuses()),
                Tables\Filters\Filter::make('created_at')
                    ->label('Tanggal Menyatakan Minat')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Dari'),
                        Forms\Components\DatePicker::make('until')->label('Sampai'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'] ?? null, fn (Builder $query, $date) => $query->whereDate('created_at', '>=', $date))
                        ->when($data['until'] ?? null, fn (Builder $query, $date) => $query->whereDate('created_at', '<=', $date))),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('profile')
                    ->label('Lihat Profil')
                    ->icon('heroicon-o-user-circle')
                    ->modalHeading(fn (EventFollower $record): string => 'Profil ' . $record->user->name)
                    ->modalContent(fn (EventFollower $record) => view('filament.event-follower-profile', ['record' => $record->load('user.profile')]))
                    ->modalSubmitAction(false),
                Tables\Actions\EditAction::make()->label('Ubah Status'),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('updateStatus')
                        ->label('Ubah Status Massal')
                        ->icon('heroicon-o-arrow-path')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Status Baru')
                                ->options(EventFollower::statuses())
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $records->each(fn (EventFollower $record) => $record->update(['status' => $data['status']]));
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user.profile', 'event'])
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventFollowers::route('/'),
        ];
    }
}
