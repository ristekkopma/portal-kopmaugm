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
    // protected static ?string $model = null;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Administrasi';
    protected static ?string $navigationLabel = 'Riwayat Peminjaman';
    
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

            Forms\Components\DatePicker::make('tanggal_pinjam')->required(),
            Forms\Components\DatePicker::make('tanggal_kembali'),
            Forms\Components\Select::make('status')
                ->options([
                    'dipinjam'     => 'Dipinjam',
                    'dikembalikan' => 'Dikembalikan',
                    'terlambat'    => 'Terlambat',
                ])
                ->default('dipinjam'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('user.name')->label('Peminjam'),
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
            // 'create' => Pages\CreateBorrowing::route('/create'),
            // 'edit' => Pages\EditBorrowing::route('/{record}/edit'),
        ];
    }
}
