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
    protected static ?string $navigationLabel = 'Katalog';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Library';

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('cover_image')->label('Cover'),
            Tables\Columns\TextColumn::make('title_book')->searchable(),
            Tables\Columns\TextColumn::make('author'),
            Tables\Columns\TextColumn::make('category'),
            Tables\Columns\BadgeColumn::make('status')->colors([
                'success' => 'available',
                'danger' => 'no available',
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

            TextEntry::make('title_book')->label('Title'),
            TextEntry::make('author')->label('Author'),
            TextEntry::make('category')->label('Category'),
            TextEntry::make('status')->label('Status'),
            TextEntry::make('description')->label('Description')->columnSpanFull(),
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
