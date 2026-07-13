<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventNotificationBatchResource\Pages;
use App\Jobs\SendEventNotificationEmail;
use App\Models\EventNotificationBatch;
use App\Models\EventNotificationLog;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventNotificationBatchResource extends Resource
{
    protected static ?string $model = EventNotificationBatch::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Manajemen Event';

    protected static ?string $navigationLabel = 'Notifikasi Email Event';

    protected static ?string $modelLabel = 'Batch Notifikasi';

    protected static ?string $pluralModelLabel = 'Notifikasi Email Event';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view_event_notification_batches') ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('event.title')->label('Event')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn (string $state): string => static::statusLabel($state))
                    ->color(fn (string $state): string => match ($state) {
                        'sent' => 'success',
                        'failed' => 'danger',
                        'processing' => 'warning',
                        default => 'info',
                    }),
                Tables\Columns\TextColumn::make('total_recipients')->label('Total'),
                Tables\Columns\TextColumn::make('recipients_sent_count')->label('Berhasil')
                    ->getStateUsing(fn (EventNotificationBatch $record): int => $record->recipients()->where('status', 'sent')->count()),
                Tables\Columns\TextColumn::make('failed_recipients')->label('Gagal')->color('danger'),
                Tables\Columns\TextColumn::make('verifier.name')->label('Diverifikasi Oleh'),
                Tables\Columns\TextColumn::make('verified_at')->label('Diverifikasi')->dateTime('d M Y, H:i')->sortable(),
                Tables\Columns\TextColumn::make('sent_at')->label('Selesai')->dateTime('d M Y, H:i')->placeholder('-'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'queued' => 'Menunggu',
                    'processing' => 'Sedang Dikirim',
                    'sent' => 'Berhasil',
                    'failed' => 'Sebagian Gagal',
                ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat Penerima')->modalWidth('7xl'),
                Tables\Actions\Action::make('retry')
                    ->label('Coba Lagi Email Gagal')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (EventNotificationBatch $record): bool => $record->failed_recipients > 0 && auth()->user()->can('retry_event_notifications'))
                    ->action(function (EventNotificationBatch $record): void {
                        abort_unless(auth()->user()->can('retry_event_notifications'), 403);
                        $recipients = $record->recipients()->where('status', 'failed')->get();
                        $record->update(['status' => 'queued', 'failed_recipients' => 0, 'last_error' => null]);

                        foreach ($recipients as $recipient) {
                            $recipient->update(['status' => 'queued', 'failure_reason' => null]);
                            SendEventNotificationEmail::dispatch($recipient->id);
                        }

                        EventNotificationLog::create([
                            'batch_id' => $record->id,
                            'event_id' => $record->event_id,
                            'user_id' => auth()->id(),
                            'action' => 'retry_failed_recipients',
                            'metadata' => ['total' => $recipients->count()],
                        ]);
                        Notification::make()->title($recipients->count().' email dimasukkan kembali ke antrean')->success()->send();
                    }),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Ringkasan')->columns(4)->schema([
                Infolists\Components\TextEntry::make('event.title')->label('Event')->columnSpan(2),
                Infolists\Components\TextEntry::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn (string $state): string => static::statusLabel($state)),
                Infolists\Components\TextEntry::make('total_recipients')->label('Total Penerima'),
                Infolists\Components\TextEntry::make('last_error')->label('Keterangan')->placeholder('-')->columnSpanFull(),
            ]),
            Infolists\Components\Section::make('Penerima')->schema([
                Infolists\Components\RepeatableEntry::make('recipients')->hiddenLabel()->schema([
                    Infolists\Components\TextEntry::make('name')->label('Nama'),
                    Infolists\Components\TextEntry::make('email')->label('Email'),
                    Infolists\Components\TextEntry::make('status')->label('Status')->badge()
                        ->formatStateUsing(fn (string $state): string => match ($state) {
                            'queued' => 'Menunggu',
                            'processing' => 'Sedang Dikirim',
                            'sent' => 'Berhasil',
                            'failed' => 'Gagal',
                            default => ucfirst($state),
                        }),
                    Infolists\Components\TextEntry::make('failure_reason')->label('Alasan Kegagalan')->placeholder('-')->columnSpanFull(),
                ])->columns(3),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListEventNotificationBatches::route('/')];
    }

    private static function statusLabel(string $status): string
    {
        return match ($status) {
            'draft' => 'Draf',
            'verified' => 'Terverifikasi',
            'queued' => 'Menunggu',
            'processing' => 'Sedang Dikirim',
            'sent' => 'Berhasil',
            'failed' => 'Sebagian Gagal',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($status),
        };
    }
}
