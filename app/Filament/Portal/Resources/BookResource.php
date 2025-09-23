<?php

namespace App\Filament\Portal\Resources;

use App\Filament\Portal\Resources\BookResource\Pages;
use App\Filament\Portal\Resources\BookResource\RelationManagers;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;
    protected static ?string $navigationLabel = 'Katalog Buku';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Perpustakaan';

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('cover_image')->label('Cover'),
            Tables\Columns\TextColumn::make('judul_buku')->searchable(),
            Tables\Columns\TextColumn::make('penulis'),
            Tables\Columns\TextColumn::make('kategori'),
            Tables\Columns\BadgeColumn::make('status')->colors([
                'success' => 'tersedia',
                'danger' => 'tidak tersedia',
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
            'index' => Pages\ListBooks::route('/'),
            // 'create' => Pages\CreateBook::route('/create'),
            // 'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
