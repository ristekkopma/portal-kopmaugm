<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Manajemen Event';

    protected static ?string $navigationLabel = 'Events';

    protected static ?string $modelLabel = 'Event';

    protected static ?string $pluralModelLabel = 'Events';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Event')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Event')
                            ->required()
                            ->maxLength(200)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('organizer_name')
                            ->label('Nama Penyelenggara')
                            ->default('Kopma UGM')
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('organizer_logo')
                            ->label('Logo Penyelenggara')
                            ->image()
                            ->disk('public')
                            ->directory('events/organizers')
                            ->visibility('public')
                            ->maxSize(2048),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Lengkap')
                            ->rows(6)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('category')
                            ->label('Kategori Event')
                            ->options([
                                'urgent' => 'Urgent',
                                'bulanan' => 'Bulanan',
                                'tahunan' => 'Tahunan',
                            ])
                            ->default('bulanan')
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status Publikasi')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'ongoing' => 'Ongoing',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('published')
                            ->required(),

                        Forms\Components\FileUpload::make('thumbnail')
                            ->label('Thumbnail Card')
                            ->image()
                            ->disk('public')
                            ->directory('events/thumbnails')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(5120),

                        Forms\Components\FileUpload::make('banner')
                            ->label('Banner Detail')
                            ->image()
                            ->disk('public')
                            ->directory('events/banners')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(5120)
                            ->helperText('JPG, JPEG, PNG, atau WEBP. Maksimal 5 MB.'),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Waktu Publikasi')
                            ->seconds(false)
                            ->default(now()),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Jadwal Event')
                    ->schema([
                        Forms\Components\DatePicker::make('event_date')
                            ->label('Tanggal Pelaksanaan')
                            ->required(),

                        Forms\Components\TimePicker::make('start_time')
                            ->label('Jam Mulai')
                            ->seconds(false)
                            ->required(),

                        Forms\Components\TimePicker::make('end_time')
                            ->label('Jam Selesai')
                            ->seconds(false)
                            ->afterOrEqual('start_time')
                            ->required(),

                        Forms\Components\Select::make('event_type')
                            ->label('Jenis Event')
                            ->options([
                                'offline' => 'Offline',
                                'online' => 'Online',
                                'hybrid' => 'Hybrid',
                            ])
                            ->default('offline')
                            ->required(),

                        Forms\Components\TextInput::make('location')
                            ->label('Lokasi atau Link Pertemuan')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('registration_deadline')
                            ->label('Batas Pendaftaran')
                            ->seconds(false),

                        Forms\Components\TextInput::make('contact_person')
                            ->label('Narahubung')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Pendaftaran dan Informasi Tambahan')
                    ->schema([
                        Forms\Components\TextInput::make('registration_url')
                            ->label('Link Pendaftaran Google Form')
                            ->url()
                            ->prefixIcon('heroicon-o-link')
                            ->placeholder('https://forms.gle/...')
                            ->helperText('Disarankan menggunakan forms.gle atau docs.google.com/forms.')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('rundown')
                            ->label('Rundown / Informasi Tambahan')
                            ->rows(6),

                        Forms\Components\Textarea::make('terms')
                            ->label('Syarat dan Ketentuan')
                            ->rows(6),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Gambar')
                    ->disk('public')
                    ->getStateUsing(fn (Event $record): ?string => $record->thumbnail ?: $record->banner ?: $record->image)
                    ->square(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Event')
                    ->searchable()
                    ->wrap()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'urgent' => 'danger',
                        'bulanan' => 'success',
                        'tahunan' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('event_date')
                    ->label('Tanggal Event')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn (Event $record): string => $record->status_label)
                    ->badge()
                    ->color(fn (Event $record): string => match ($record->status_color) {
                        'green' => 'success',
                        'red' => 'danger',
                        'orange' => 'warning',
                        'blue' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('interested_total')
                    ->label('Total Peminat')
                    ->suffix(' orang')
                    ->url(fn (Event $record): ?string => auth()->user()->can('view_event_followers')
                        ? route('filament.admin.resources.event-followers.index', [
                            'tableFilters' => ['event_id' => ['value' => $record->id]],
                        ])
                        : null)
                    ->color('primary'),

                Tables\Columns\TextColumn::make('registered_total')
                    ->label('Terdaftar')
                    ->suffix(' orang'),

                Tables\Columns\TextColumn::make('attended_total')
                    ->label('Hadir')
                    ->suffix(' orang'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori Event')
                    ->options([
                        'urgent' => 'Urgent',
                        'bulanan' => 'Bulanan',
                        'tahunan' => 'Tahunan',
                    ]),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('followers')
                    ->label('Lihat Peminat')
                    ->icon('heroicon-o-user-group')
                    ->url(fn (Event $record): string => route('filament.admin.resources.event-followers.index', [
                        'tableFilters' => ['event_id' => ['value' => $record->id]],
                    ]))
                    ->visible(fn (): bool => auth()->user()->can('view_event_followers')),
                Tables\Actions\Action::make('exportFollowers')
                    ->label('Export Peminat')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Event $record): string => route('admin.event-followers.export', ['event_id' => $record->id]))
                    ->visible(fn (): bool => auth()->user()->can('export_event_followers')),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount([
                'activeFollowers as interested_total',
                'followers as registered_total' => fn (Builder $query) => $query->where('status', 'registered'),
                'followers as attended_total' => fn (Builder $query) => $query->where('status', 'attended'),
            ])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
