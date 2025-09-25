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

    protected static ?string $navigationGroup = 'Library';
    protected static ?string $navigationLabel = 'Booking';

     public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('book_id')
                ->relationship('book', 'title_book')
                ->searchable()
                ->required(),

            Forms\Components\DatePicker::make('date_booking')->required(),
            Forms\Components\Textarea::make('note'),
            Forms\Components\Select::make('status_booking')
                ->options([
                    'waiting'   => 'Waiting',
                    'approve'  => 'Approve',
                    'rejected'    => 'Rejected',
                ])
                ->default('waiting'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('user.name')->label('Name'),
            Tables\Columns\TextColumn::make('book.title_book')->label('Book'),
            Tables\Columns\TextColumn::make('date_booking')->date(),
            Tables\Columns\BadgeColumn::make('status_booking')->colors([
                'warning' => 'waiting',
                'success' => 'approve',
                'danger'  => 'rejected',
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
