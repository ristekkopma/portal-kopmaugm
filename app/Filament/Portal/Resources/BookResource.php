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
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;

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
        ])
        ->actions([
            Tables\Actions\ViewAction::make(), // tombol View
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function infolist(Infolist $infolist): Infolist
{
    return $infolist
        ->schema([
            ImageEntry::make('cover_image')
                ->disk('public')
                ->label('Cover')
                ->columnSpanFull(),

            TextEntry::make('judul_buku')->label('Judul'),
            TextEntry::make('penulis')->label('Penulis'),
            TextEntry::make('kategori')->label('Kategori'),
            TextEntry::make('status')->label('Status'),
            TextEntry::make('deskripsi')->label('Deskripsi')->columnSpanFull(),
        ]);
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'view'  => Pages\ViewBook::route('/{record}'),
            // 'create' => Pages\CreateBook::route('/create'),
            // 'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
