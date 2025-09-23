<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Administrasi';
    protected static ?string $navigationLabel = 'Booking Buku';

     public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('book_id')
                ->relationship('book', 'judul_buku')
                ->searchable()
                ->required(),

            Forms\Components\DatePicker::make('tanggal_booking')->required(),
            Forms\Components\Textarea::make('catatan'),
            Forms\Components\Select::make('status_booking')
                ->options([
                    'menunggu'   => 'Menunggu',
                    'disetujui'  => 'Disetujui',
                    'ditolak'    => 'Ditolak',
                ])
                ->default('menunggu'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('user.name')->label('Anggota'),
            Tables\Columns\TextColumn::make('book.judul_buku')->label('Buku'),
            Tables\Columns\TextColumn::make('tanggal_booking')->date(),
            Tables\Columns\BadgeColumn::make('status_booking')->colors([
                'warning' => 'menunggu',
                'success' => 'disetujui',
                'danger'  => 'ditolak',
            ]),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
