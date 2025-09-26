<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BorrowingResource\Pages;
use App\Filament\Resources\BorrowingResource\RelationManagers;
use App\Models\Borrowing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BorrowingResource extends Resource
{
    protected static ?string $model = Borrowing::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Library';
    protected static ?string $navigationLabel = 'History';
    protected static ?string $navigationGroupShort = '4';
    
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

            Forms\Components\DatePicker::make('date_borrowing')->required(),
            Forms\Components\DatePicker::make('date_return'),
            Forms\Components\Select::make('status')
                ->options([ 
                    'borrowed'     => 'Borrowed',
                    'returned' => 'Returned',
                    'late'    => 'late',
                ])
                ->default('Borrowed'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('user.name')->label('Peminjam'),
            Tables\Columns\TextColumn::make('book.title_book')->label('Book'),
            Tables\Columns\TextColumn::make('date_borrowing')->date(),
            Tables\Columns\TextColumn::make('date_return')->date(),
            Tables\Columns\BadgeColumn::make('status')->colors([
                'warning' => 'borroed',
                'success' => 'returned',
                'danger'  => 'late',
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
            'index' => Pages\ListBorrowings::route('/'),
            // 'create' => Pages\CreateBorrowing::route('/create'),
            // 'edit' => Pages\EditBorrowing::route('/{record}/edit'),
        ];
    }
}
