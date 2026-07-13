<?php

namespace App\Filament\Portal\Resources;

use App\Filament\Portal\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Events';

    protected static ?string $modelLabel = 'Event';

    protected static ?string $pluralModelLabel = 'Events';

    protected static bool $shouldRegisterNavigation = false;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                Stack::make([
                    Tables\Columns\ImageColumn::make('banner')
                        ->label('Banner')
                        ->disk('public')
                        ->getStateUsing(fn (Event $record): ?string => $record->thumbnail ?: $record->banner ?: $record->image)
                        ->height('13rem')
                        ->width('100%')
                        ->extraImgAttributes(['class' => 'w-full object-cover']),

                    Stack::make([
                        Tables\Columns\TextColumn::make('category')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => Event::categories()[$state] ?? 'Event')
                            ->color(fn (?string $state): string => match ($state) {
                                Event::CATEGORY_URGENT => 'danger',
                                Event::CATEGORY_TAHUNAN => 'info',
                                default => 'success',
                            }),
                        Tables\Columns\TextColumn::make('title')
                            ->weight('bold')
                            ->wrap()
                            ->searchable(),
                        Tables\Columns\TextColumn::make('organizer_name')
                            ->label('Penyelenggara')
                            ->default('Kopma UGM')
                            ->icon('heroicon-o-building-office-2')
                            ->color('gray'),
                        Tables\Columns\TextColumn::make('event_date')
                            ->label('Tanggal Event')
                            ->date('d F Y')
                            ->icon('heroicon-o-calendar')
                            ->color('gray'),
                        Tables\Columns\TextColumn::make('status')
                            ->badge()
                            ->getStateUsing(fn (Event $record): string => $record->status_label)
                            ->color(fn (Event $record): string => match ($record->status_color) {
                                'green' => 'success',
                                'red' => 'danger',
                                'orange' => 'warning',
                                'blue' => 'info',
                                default => 'gray',
                            }),
                        Tables\Columns\TextColumn::make('followers_count')
                            ->label('Peminat')
                            ->getStateUsing(fn (Event $record): string => $record->active_followers_count . ' orang ingin mengikuti')
                            ->icon('heroicon-o-user-group')
                            ->color('gray'),
                    ])->space(2),
                ])->space(3),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->recordUrl(fn (Event $record): string => static::getUrl('view', ['record' => $record]))
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(Event::categories()),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'published' => 'Published',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                ]),
            ])
            ->bulkActions([])
            ->emptyStateHeading('Belum ada event')
            ->emptyStateDescription('Event yang diterbitkan admin akan tampil di halaman ini.');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\ImageEntry::make('banner')
                            ->hiddenLabel()
                            ->disk('public')
                            ->getStateUsing(fn (Event $record): ?string => $record->banner ?: $record->thumbnail ?: $record->image)
                            ->defaultImageUrl(asset('images/logo.png'))
                            ->height('28rem')
                            ->extraImgAttributes(['class' => 'w-full object-cover rounded-xl']),
                    ]),

                Infolists\Components\Section::make('Informasi Event')
                    ->columns(3)
                    ->schema([
                        Infolists\Components\ImageEntry::make('organizer_logo')
                            ->label('Logo Penyelenggara')
                            ->disk('public')
                            ->circular()
                            ->defaultImageUrl(asset('images/logo.png')),
                        Infolists\Components\TextEntry::make('organizer_name')
                            ->label('Penyelenggara')
                            ->default('Kopma UGM'),
                        Infolists\Components\TextEntry::make('category')
                            ->label('Kategori')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => Event::categories()[$state] ?? 'Event'),
                        Infolists\Components\TextEntry::make('schedule_start')
                            ->label('Mulai')
                            ->dateTime('d F Y, H:i')
                            ->icon('heroicon-o-calendar'),
                        Infolists\Components\TextEntry::make('schedule_end')
                            ->label('Selesai')
                            ->dateTime('d F Y, H:i')
                            ->icon('heroicon-o-clock'),
                        Infolists\Components\TextEntry::make('location')
                            ->label('Lokasi')
                            ->formatStateUsing(fn (?string $state, Event $record): string => ucfirst($record->event_type ?: 'offline') . ' · ' . ($state ?: 'Akan diumumkan'))
                            ->icon('heroicon-o-map-pin'),
                        Infolists\Components\TextEntry::make('registration_deadline')
                            ->label('Batas Pendaftaran')
                            ->dateTime('d F Y, H:i')
                            ->placeholder('-'),
                        Infolists\Components\TextEntry::make('contact_person')
                            ->label('Narahubung')
                            ->placeholder('-'),
                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->getStateUsing(fn (Event $record): string => $record->status_label),
                    ]),

                Infolists\Components\Section::make('Tentang Event')
                    ->schema([
                        Infolists\Components\TextEntry::make('description')
                            ->hiddenLabel()
                            ->placeholder('Informasi event belum tersedia.'),
                        Infolists\Components\TextEntry::make('rundown')
                            ->label('Rundown / Informasi Tambahan')
                            ->placeholder('-'),
                        Infolists\Components\TextEntry::make('terms')
                            ->label('Syarat dan Ketentuan')
                            ->placeholder('-'),
                    ]),

                Infolists\Components\Section::make('Review')
                    ->description(fn (Event $record): string => number_format((float) $record->reviews()->avg('rating'), 1) . ' dari 5 · ' . $record->reviews()->count() . ' review')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('reviews')
                            ->hiddenLabel()
                            ->schema([
                                Infolists\Components\TextEntry::make('user.name')->label('Pengguna')->weight('bold'),
                                Infolists\Components\TextEntry::make('rating')->label('Rating')->suffix(' / 5')->badge()->color('warning'),
                                Infolists\Components\TextEntry::make('review')->label('Ulasan')->columnSpanFull(),
                                Infolists\Components\TextEntry::make('updated_at')->label('Diperbarui')->since(),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', '!=', 'draft')
            ->withCount('activeFollowers')
            ->with(['reviews.user']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'view' => Pages\ViewEvent::route('/{record}'),
        ];
    }
}
