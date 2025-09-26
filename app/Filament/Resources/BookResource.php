<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Library';
    protected static ?string $navigationLabel = 'Katalog';
    protected static ?string $navigationGroupShort = '4';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title_book')->required(),
            Forms\Components\TextInput::make('author'),
            Forms\Components\TextInput::make('publisher'),
            Forms\Components\TextInput::make('year_publish')->numeric(),
            Forms\Components\TextInput::make('isbn'),
            Forms\Components\TextInput::make('category'),
            Forms\Components\Textarea::make('description'),
                        // Form
            Forms\Components\FileUpload::make('cover_image')
                ->image()
                ->directory('books')
                ->label('Cover Book'),

            Forms\Components\TextInput::make('stock')
                ->numeric()
                ->minValue(0)
                ->required(),
            Forms\Components\Select::make('status')
                ->options([
                    'available' => 'Available',
                    'no available' => 'No Available',
                    
                ])
                ->default('available'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('title_book')->searchable(),
            Tables\Columns\TextColumn::make('author')->sortable(),
            Tables\Columns\TextColumn::make('category'),
            Tables\Columns\ImageColumn::make('cover_image')->label('Cover'),
Tables\Columns\TextColumn::make('stock')->label('Stock'),

            Tables\Columns\BadgeColumn::make('status')->colors([
                'success' => 'available',
                'warning' => 'no available',
                
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
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
