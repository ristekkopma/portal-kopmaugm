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
    protected static ?string $navigationGroup = 'Broadcast';
    protected static ?string $navigationLabel = 'Announcements';

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
                    ->label('Title Broadcast')
                    ->searchable()
                    ->limit(40),


               Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->formatStateUsing(fn($state) => match ($state) {
                    'sending' => 'Sending',
                    'queued'  => 'Queued',
                    'failed'  => 'Failed',
                    default   => 'Default'
                })
                ->color(fn($state) => match ($state) {
                    'sending' => 'success',
                    'queued'  => 'warning',
                    'failed'  => 'danger',
                    default   => 'gray'
                }),


                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('M d, Y H:i'),
            ])
            
            ->actions([
                Tables\Actions\Action::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->url(fn($record) => route('filament.admin.resources.announcements.view', $record)),
     

            // 🔴 Tombol hapus per baris
            Tables\Actions\DeleteAction::make()
                ->label('Hapus')
                ->requiresConfirmation() // biar ada popup konfirmasi
                ->color('danger')
                ->icon('heroicon-o-trash'),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make()
                ->label('Hapus Terpilih'),
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
