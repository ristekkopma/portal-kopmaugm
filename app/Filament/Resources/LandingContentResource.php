<?php

namespace App\Filament\Resources;

namespace App\Filament\Resources;

use App\Models\LandingContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\LandingContentResource\Pages;
use Filament\Tables\Table;


class LandingContentResource extends Resource
{
    protected static ?string $model = LandingContent::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = 'Pengaturan Umum';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('hero_title')->required(),
            Forms\Components\Textarea::make('hero_paragraph')->rows(3),
            Forms\Components\FileUpload::make('hero_image')->image()->directory('landing'),

            Forms\Components\Textarea::make('intro_text')->label('Intro Website'),
            Forms\Components\FileUpload::make('step_image_1')->image()->directory('landing'),
            Forms\Components\FileUpload::make('step_image_2')->image()->directory('landing'),
            Forms\Components\FileUpload::make('step_image_3')->image()->directory('landing'),

            Forms\Components\Textarea::make('alamat'),
            Forms\Components\Textarea::make('map_iframe')->label('Embed Google Map'),

            Forms\Components\TextInput::make('instagram')->label('Instagram URL'),
            Forms\Components\TextInput::make('youtube')->label('YouTube URL'),
            Forms\Components\TextInput::make('twitter')->label('Twitter URL'),
            Forms\Components\TextInput::make('linkedin')->label('LinkedIn URL'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('hero_title')->limit(50),
            Tables\Columns\TextColumn::make('updated_at')->label('Updated')->since(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->defaultSort('updated_at', 'desc')
        ->paginated(false); // karena hanya 1
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLandingContents::route('/'),
            'edit' => Pages\EditLandingContent::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}
