<?php

namespace App\Filament\Portal\Resources;

use App\Filament\Portal\Resources\BookingResource\Pages;
use App\Filament\Portal\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;


class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Booking Buku';
    protected static ?string $navigationGroup = 'Perpustakaan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // user_id otomatis dari Auth, tidak ditampilkan
            Forms\Components\Hidden::make('user_id')
                ->default(fn () => Auth::id()),

           

            Forms\Components\DatePicker::make('tanggal_booking')
                ->required()
                ->default(now()),

                Forms\Components\Select::make('book_id')
    ->relationship('book', 'judul_buku')
    ->searchable()
    ->required()
    ->afterStateHydrated(function ($state, $set) {
        $book = \App\Models\Book::find($state);
        if ($book) $set('cover_preview', $book->cover_image);
    }),

    Forms\Components\FileUpload::make('cover_preview')->image()->disabled()->label('Preview Cover'),

            Forms\Components\Textarea::make('catatan'),
            

            // status_booking tidak bisa diisi member → default "menunggu"
            Forms\Components\Hidden::make('status_booking')
                ->default('menunggu'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => 
                $query->where('user_id', Auth::id()) // hanya booking milik member
            )
            ->columns([
                Tables\Columns\TextColumn::make('book.judul_buku')->label('Buku'),
                Tables\Columns\TextColumn::make('tanggal_booking')->date(),
                Tables\Columns\BadgeColumn::make('status_booking')
                    ->colors([
                        'warning' => 'menunggu',
                        'success' => 'disetujui',
                        'danger'  => 'ditolak',
                    ]),

             Tables\Columns\TextColumn::make('catatan')
                ->label('Catatan')
                ->wrap() // supaya teks panjang otomatis kebungkus
                ->toggleable(), // bisa disembunyikan via column toggle
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
            // 'create' => Pages\CreateBooking::route('/create'),
            // 'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
