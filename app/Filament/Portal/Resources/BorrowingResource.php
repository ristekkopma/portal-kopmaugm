<?php

namespace App\Filament\Portal\Resources;

use App\Filament\Portal\Resources\BorrowingResource\Pages;
use App\Filament\Portal\Resources\BorrowingResource\RelationManagers;
use App\Models\Borrowing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class BorrowingResource extends Resource
{
    protected static ?string $model = Borrowing::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'History';
    protected static ?string $navigationGroup = 'Library';

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => 
                $query->where('user_id', Auth::id()) // hanya data milik member
            )
            ->columns([
                Tables\Columns\TextColumn::make('book.tiltle_book')->label('Book'),
                Tables\Columns\TextColumn::make('date_borrowing')->date(),
                Tables\Columns\TextColumn::make('date_return')->date(),
                Tables\Columns\BadgeColumn::make('status')->colors([
                    'warning' => 'borrowed',
                    'success' => 'return',
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
