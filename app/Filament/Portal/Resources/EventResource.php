<?php

namespace App\Filament\Portal\Resources;

use App\Filament\Portal\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
            ->defaultSort('opened_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->disk('public')
                    ->square(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Event')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Event::CATEGORY_URGENT => 'danger',
                        Event::CATEGORY_BULANAN => 'success',
                        Event::CATEGORY_TAHUNAN => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => Event::categories()[$state] ?? ucfirst($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan')
                    ->limit(80)
                    ->wrap()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('opened_at')
                    ->label('Mulai')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('closed_at')
                    ->label('Selesai')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(Event::categories()),
            ])
            ->actions([
                Tables\Actions\Action::make('openLink')
                    ->label('Buka Link')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Event $record): ?string => $record->url)
                    ->openUrlInNewTab()
                    ->visible(fn (Event $record): bool => filled($record->url)),
            ])
            ->bulkActions([])
            ->emptyStateHeading('Belum ada event')
            ->emptyStateDescription('Event yang ditambahkan admin akan tampil di halaman ini.');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
        ];
    }
}
