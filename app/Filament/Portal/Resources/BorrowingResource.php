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
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Riwayat Peminjaman';
    protected static ?string $navigationGroup = 'Perpustakaan';

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => 
                $query->where('user_id', Auth::id()) // hanya data milik member
            )
            ->columns([
                Tables\Columns\TextColumn::make('book.judul_buku')->label('Buku'),
                Tables\Columns\TextColumn::make('tanggal_pinjam')->date(),
                Tables\Columns\TextColumn::make('tanggal_kembali')->date(),
                Tables\Columns\BadgeColumn::make('status')->colors([
                    'warning' => 'dipinjam',
                    'success' => 'dikembalikan',
                    'danger'  => 'terlambat',
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
            'create' => Pages\CreateBorrowing::route('/create'),
            'edit' => Pages\EditBorrowing::route('/{record}/edit'),
        ];
    }
}
