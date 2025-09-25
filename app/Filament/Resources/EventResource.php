<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Components as AppComponents;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    

    public static function getModelLabel(): string
    {
        return __('Event');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Management');
    }

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(200),
                        Forms\Components\Textarea::make('description')
                            ->rows(3),
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->maxSize(2048)
                            ->imageEditor(),
                        Forms\Components\TextInput::make('url')
                            ->url()
                            ->default('https://')
                            ->prefixIcon('heroicon-s-link')
                            ->helperText('Starts with http:// or https://'),
                    ]),
                    Forms\Components\Section::make([
                        Forms\Components\DateTimePicker::make('opened_at')
                            ->seconds(false)
                            ->default(now())
                            ->required(),
                        Forms\Components\DateTimePicker::make('closed_at')
                            ->seconds(false)
                            ->required(),
                    ]),
                ])->columnSpan(2),
                Forms\Components\Group::make([
                    AppComponents\Forms\TimestampPlaceholder::make(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                AppComponents\Columns\IDColumn::make(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->wrap()
                    ->sortable(),
                Tables\Columns\TextColumn::make('url'),
                Tables\Columns\TextColumn::make('opened_at')
                    ->dateTime('d F Y H:i'),
                Tables\Columns\TextColumn::make('closed_at')
                    ->dateTime('d F Y H:i'),
                AppComponents\Columns\LastModifiedColumn::make(),
                AppComponents\Columns\CreatedAtColumn::make(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
