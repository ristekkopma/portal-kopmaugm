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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Administrasi';
    protected static ?string $navigationLabel = 'Koleksi Buku';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('judul_buku')->required(),
            Forms\Components\TextInput::make('penulis'),
            Forms\Components\TextInput::make('penerbit'),
            Forms\Components\TextInput::make('tahun_terbit')->numeric(),
            Forms\Components\TextInput::make('isbn'),
            Forms\Components\TextInput::make('kategori'),
            Forms\Components\Textarea::make('deskripsi'),
                        // Form
            Forms\Components\FileUpload::make('cover_image')
                ->image()
                ->directory('books')
                ->label('Cover Buku'),

            Forms\Components\TextInput::make('stok')
                ->numeric()
                ->minValue(0)
                ->required(),
            Forms\Components\Select::make('status')
                ->options([
                    'tersedia' => 'Tersedia',
                    'dipinjam' => 'Dipinjam',
                    'rusak'    => 'Rusak',
                    'hilang'   => 'Hilang',
                ])
                ->default('tersedia'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('judul_buku')->searchable(),
            Tables\Columns\TextColumn::make('penulis')->sortable(),
            Tables\Columns\TextColumn::make('kategori'),
            Tables\Columns\ImageColumn::make('cover_image')->label('Cover'),
Tables\Columns\TextColumn::make('stok')->label('Stok'),

            Tables\Columns\BadgeColumn::make('status')->colors([
                'success' => 'tersedia',
                'warning' => 'dipinjam',
                'danger'  => ['rusak', 'hilang'],
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
