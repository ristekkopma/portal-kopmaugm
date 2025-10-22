<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Models\Announcement;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationGroup = 'Komunikasi';
    protected static ?string $navigationLabel = 'Pengumuman';

    // 🧾 FORM
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
         ->columns(1)
        ->schema([
            Forms\Components\TextInput::make('title')
                ->label('Nama Pesan')
                ->required()
                ->maxLength(150),

            Forms\Components\Textarea::make('message')
                ->label('Isi Pesan')
                ->rows(6)
                ->required()
                ->helperText('Gunakan {nama} untuk menyebut nama penerima otomatis.'),

            Forms\Components\Checkbox::make('is_broadcast')
                ->label('Kirim ke semua user?')
                ->reactive(),
                
            Forms\Components\Select::make('target_user_ids')
                ->label('Pilih User (multi)')
                ->multiple()
                ->options(fn() => User::orderBy('name')->pluck('name', 'id'))
                ->searchable()
                ->visible(fn($get) => !$get('is_broadcast'))
                ->helperText('Biarkan kosong jika broadcast.'),

                
        ]);
    }

    // 🧩 TABEL
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                        ->rowIndex(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Nama Broadcast')
                    ->searchable()
                    ->limit(40),

                // Tables\Columns\IconColumn::make('is_broadcast')
                //     ->label('Broadcast')
                //     ->boolean()
                //     ->trueIcon('heroicon-o-check-circle')
                //     ->falseIcon('heroicon-o-x-circle')
                //     ->color(fn($state) => $state ? 'success' : 'danger'),

                Tables\Columns\IconColumn::make('status_icon')
                    ->label('Status')
                    ->getStateUsing(fn($record) => $record->status)
                    ->icon(fn($state) => match ($state) {
                        'sending' => 'heroicon-o-check-circle',
                        'queued'  => 'heroicon-o-clock',
                        'failed'  => 'heroicon-o-x-circle',
                        default   => 'heroicon-o-minus-circle'
                    })
                    ->color(fn($state) => match ($state) {
                        'sending' => 'success',
                        'queued'  => 'warning',
                        'failed'  => 'danger',
                        default   => 'gray'
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('M d, Y H:i'),
            ])
            ->actions([
                Tables\Actions\Action::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->url(fn($record) => route('filament.admin.resources.announcements.view', $record)),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'view'   => Pages\ViewAnnouncement::route('/{record}'),
            'edit'   => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
