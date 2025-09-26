<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Filament\Resources\DocumentResource\RelationManagers;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Components as AppComponents;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationLabel = 'Document';
    protected static ?string $navigationGroupShort = '2';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\Select::make('member_id')
                            ->helperText('Biarkan kosong jika merupakan dokumen KOPMA')
                            ->relationship('member', 'name')
                            ->preload()
                            ->searchable()
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->user->name),
                        Forms\Components\TextInput::make('name')
                            ->label('Title')
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull()
                    ])->columns(2),
                    Forms\Components\Section::make([
                        Forms\Components\FileUpload::make('path')
                            ->label('Attachment')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->maxSize(2048)
                            ->required()
                    ])
                ])->columnSpan(2),
                Forms\Components\Group::make([
                    AppComponents\Forms\TimestampPlaceholder::make()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('member.user.name')
                    ->label('Owner')
                    ->placeholder('Kopma document')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Title'),
                Tables\Columns\TextColumn::make('description'),
                AppComponents\Columns\LastModifiedColumn::make(),
                AppComponents\Columns\CreatedAtColumn::make(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('member_id')
                    ->relationship('member', 'code')
                    ->label('Owner')
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->user->name),
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->button()
                    ->outlined()
                    ->url(fn(Document $record) => asset('storage/' . $record->path), true),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->label(false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
