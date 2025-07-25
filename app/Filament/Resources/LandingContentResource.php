<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LandingContentResource\Pages;
use App\Filament\Resources\LandingContentResource\RelationManagers;
use App\Models\LandingContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Enums\UserRole; 
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Get;

use Filament\Tables\Columns\TextColumn;


class LandingContentResource extends Resource
{
    protected static ?string $model = LandingContent::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('section')
                ->required()
                ->label('Bagian (Section)')
                ->placeholder('Contoh: hero, tata_cara, footer'),

            Select::make('type')
                ->options([
                    'title' => 'Judul',
                    'subtitle' => 'Subjudul',
                    'text' => 'Teks',
                    'image' => 'Gambar',
                    'embed' => 'Embed (maps/video)',
                ])
                ->required()
                ->label('Tipe Konten'),

            Textarea::make('content')
                ->label('Isi Konten (Teks/Link)')
                ->rows(5)
                ->visible(fn (Get $get) => $get('type') !== 'image'),

            FileUpload::make('content')
                ->directory('landing')
                ->label('Upload Gambar')
                ->visible(fn (Get $get) => $get('type') === 'image'),

            TextInput::make('order')
                ->numeric()
                ->default(0)
                ->label('Urutan Tampil'),
        ]);
}


    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('section')->sortable()->searchable(),
            TextColumn::make('type')->sortable(),
            TextColumn::make('content')->limit(50)->wrap(),
            TextColumn::make('order')->sortable(),
        ])
        ->defaultSort('order');
}

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function canAccess(): bool
{
    return auth()->check() && auth()->user()->role === UserRole::Admin;
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLandingContents::route('/'),
            'create' => Pages\CreateLandingContent::route('/create'),
            'edit' => Pages\EditLandingContent::route('/{record}/edit'),
        ];
    }
}
