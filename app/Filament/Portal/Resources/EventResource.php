<?php

namespace App\Filament\Portal\Resources;

use App\Filament\Portal\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

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
                        ->hiddenLabel()
                        ->disk('public')
                        ->getStateUsing(fn (Event $record): ?string => $record->thumbnail ?: $record->banner ?: $record->image)
                        ->height('13rem')
                        ->width('100%')
                        ->defaultImageUrl(asset('images/logo.png'))
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
                        Tables\Columns\TextColumn::make('title')->weight('bold')->limit(80)->wrap()->searchable(),
                        Tables\Columns\TextColumn::make('organizer_name')
                            ->label('Penyelenggara')->default('Kopma UGM')->icon('heroicon-o-building-office-2')->color('gray'),
                        Tables\Columns\TextColumn::make('event_date')
                            ->label('Tanggal Event')->date('d F Y')->icon('heroicon-o-calendar')->color('gray'),
                        Tables\Columns\TextColumn::make('location')
                            ->label('Lokasi')->limit(45)->icon('heroicon-o-map-pin')->color('gray'),
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
                            ->getStateUsing(fn (Event $record): string => $record->active_followers_count.' orang ingin mengikuti')
                            ->icon('heroicon-o-user-group')->color('gray'),
                    ])->space(2),
                ])->space(3),
            ])
            ->contentGrid(['md' => 2, 'lg' => 3])
            ->recordAction('view')
            ->filters([
                Tables\Filters\SelectFilter::make('category')->label('Kategori')->options(Event::categories()),
                Tables\Filters\SelectFilter::make('status')->options([
                    'published' => 'Published',
                    'ongoing' => 'Ongoing',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ]),
            ], layout: FiltersLayout::AboveContent)
            ->deferFilters(false)
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Detail')
                    ->modalHeading(fn (Event $record): string => $record->title)
                    ->modalWidth(MaxWidth::SevenExtraLarge)
                    ->stickyModalHeader()
                    ->stickyModalFooter()
                    ->closeModalByEscaping()
                    ->closeModalByClickingAway()
                    ->extraModalFooterActions([
                        static::followAction(),
                        static::registerAction(),
                        static::reviewAction(),
                        Tables\Actions\Action::make('share')
                            ->label('Bagikan')->icon('heroicon-o-share')->color('gray')
                            ->action(fn () => null)
                            ->extraAttributes(fn (Event $record): array => [
                                'x-on:click' => 'navigator.clipboard.writeText('.json_encode(route('events.show', $record)).')',
                            ]),
                    ]),
            ])
            ->bulkActions([])
            ->emptyStateHeading('Tidak ada event yang sesuai dengan filter')
            ->emptyStateDescription('Ubah atau reset filter untuk melihat event lainnya.');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(3)
            ->schema([
                Infolists\Components\Section::make()
                    ->columnSpanFull()
                    ->schema([
                        Infolists\Components\ImageEntry::make('banner')
                            ->hiddenLabel()->disk('public')
                            ->getStateUsing(fn (Event $record): ?string => $record->banner ?: $record->thumbnail ?: $record->image)
                            ->defaultImageUrl(asset('images/logo.png'))
                            ->height('24rem')
                            ->extraImgAttributes(['class' => 'w-full object-cover rounded-xl']),
                    ]),
                Infolists\Components\Group::make([
                    Infolists\Components\Section::make('Tentang Event')->schema([
                        Infolists\Components\TextEntry::make('description')->hiddenLabel()->placeholder('Informasi event belum tersedia.'),
                        Infolists\Components\TextEntry::make('rundown')->label('Rundown / Informasi Tambahan')->placeholder('-'),
                        Infolists\Components\TextEntry::make('terms')->label('Syarat dan Ketentuan')->placeholder('-'),
                    ]),
                    Infolists\Components\Section::make('Review')
                        ->description(fn (Event $record): string => number_format((float) $record->reviews()->avg('rating'), 1).' dari 5 · '.$record->reviews()->count().' review')
                        ->schema([
                            Infolists\Components\RepeatableEntry::make('reviews')->hiddenLabel()->schema([
                                Infolists\Components\TextEntry::make('user.name')->label('Pengguna')->weight('bold'),
                                Infolists\Components\TextEntry::make('rating')->label('Rating')->suffix(' / 5')->badge()->color('warning'),
                                Infolists\Components\TextEntry::make('review')->label('Ulasan')->columnSpanFull(),
                                Infolists\Components\TextEntry::make('updated_at')->label('Diperbarui')->since(),
                            ])->columns(2),
                        ]),
                ])->columnSpan(['default' => 3, 'lg' => 2]),
                Infolists\Components\Group::make([
                    Infolists\Components\Section::make('Informasi Event')->schema([
                        Infolists\Components\ImageEntry::make('organizer_logo')
                            ->label('Logo Penyelenggara')->disk('public')->circular()->defaultImageUrl(asset('images/logo.png')),
                        Infolists\Components\TextEntry::make('organizer_name')->label('Penyelenggara')->default('Kopma UGM'),
                        Infolists\Components\TextEntry::make('category')->label('Kategori')->badge()
                            ->formatStateUsing(fn (?string $state): string => Event::categories()[$state] ?? 'Event'),
                        Infolists\Components\TextEntry::make('status')->label('Status')->badge()
                            ->getStateUsing(fn (Event $record): string => $record->status_label),
                        Infolists\Components\TextEntry::make('schedule_start')->label('Mulai')->dateTime('d F Y, H:i')->icon('heroicon-o-calendar'),
                        Infolists\Components\TextEntry::make('schedule_end')->label('Selesai')->dateTime('d F Y, H:i')->icon('heroicon-o-clock'),
                        Infolists\Components\TextEntry::make('location')->label('Lokasi')->icon('heroicon-o-map-pin')
                            ->formatStateUsing(fn (?string $state, Event $record): string => ucfirst($record->event_type ?: 'offline').' · '.($state ?: 'Akan diumumkan')),
                        Infolists\Components\TextEntry::make('registration_deadline')->label('Batas Pendaftaran')->dateTime('d F Y, H:i')->placeholder('-'),
                        Infolists\Components\TextEntry::make('contact_person')->label('Narahubung')->placeholder('-'),
                        Infolists\Components\TextEntry::make('followers_count')->label('Peminat')->icon('heroicon-o-user-group')
                            ->getStateUsing(fn (Event $record): string => $record->active_followers_count.' orang ingin mengikuti'),
                    ]),
                ])->columnSpan(['default' => 3, 'lg' => 1]),
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
        return ['index' => Pages\ListEvents::route('/')];
    }

    private static function followAction(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('follow')
            ->label(fn (Event $record): string => $record->activeFollowers()->where('user_id', auth()->id())->exists() ? '✓ Ingin Mengikuti' : 'Ingin Mengikuti')
            ->icon('heroicon-o-star')
            ->color(fn (Event $record): string => $record->activeFollowers()->where('user_id', auth()->id())->exists() ? 'success' : 'gray')
            ->action(function (Event $record): void {
                DB::transaction(function () use ($record): void {
                    $follower = $record->followers()->withTrashed()->where('user_id', auth()->id())->first();

                    if (! $follower) {
                        $record->followers()->create(['user_id' => auth()->id(), 'status' => 'interested']);
                    } elseif ($follower->trashed()) {
                        $follower->restore();
                        $follower->update(['status' => 'interested']);
                    } else {
                        $follower->update(['status' => $follower->status === 'cancelled' ? 'interested' : 'cancelled']);
                    }
                });

                $record->refresh();
                Notification::make()->title('Status keikutsertaan diperbarui')->success()->send();
            });
    }

    private static function registerAction(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('register')
            ->label('Daftar Event')->icon('heroicon-o-arrow-top-right-on-square')
            ->url(fn (Event $record): ?string => $record->safe_registration_url)
            ->openUrlInNewTab()
            ->visible(fn (Event $record): bool => filled($record->safe_registration_url));
    }

    private static function reviewAction(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('review')
            ->label(fn (Event $record): string => $record->reviews()->where('user_id', auth()->id())->exists() ? 'Edit Review' : 'Beri Review')
            ->icon('heroicon-o-chat-bubble-left-ellipsis')
            ->visible(fn (Event $record): bool => $record->schedule_end?->isPast() || $record->status === 'completed')
            ->fillForm(fn (Event $record): array => [
                'rating' => $record->reviews()->where('user_id', auth()->id())->value('rating'),
                'review' => $record->reviews()->where('user_id', auth()->id())->value('review'),
            ])
            ->form([
                Forms\Components\ToggleButtons::make('rating')->label('Rating')
                    ->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'])->inline()->required(),
                Forms\Components\Textarea::make('review')->label('Ulasan')->required()->minLength(2)->maxLength(2000),
            ])
            ->action(function (Event $record, array $data): void {
                $data['review'] = trim($data['review']);
                abort_if($data['review'] === '', 422);
                $record->reviews()->updateOrCreate(['user_id' => auth()->id()], $data);
                Notification::make()->title('Review berhasil disimpan')->success()->send();
            });
    }
}
