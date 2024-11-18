<?php

namespace App\Filament\Resources;

use App\Enums\CandidateStatus;
use App\Enums\Gender;
use App\Enums\Marrital;
use App\Enums\MemberStatus;
use App\Enums\MemberType;
use App\Enums\RecruitmentStatus;
use App\Enums\Religion;
use App\Filament\Resources\CandidateResource\Pages;
use App\Filament\Resources\CandidateResource\RelationManagers;
use App\Models\Candidate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Components as AppComponents;
use App\Models\Member;
use App\Models\User;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CandidateResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Membership';

    protected static ?string $modelLabel = 'Candidate';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make('Membership')
                        ->schema([
                            Forms\Components\Select::make('user_id')
                                ->relationship('user', 'name')
                                ->preload()
                                ->required()
                                ->createOptionForm([
                                    Forms\Components\Group::make([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->minLength(3)
                                            ->maxLength(200),
                                        Forms\Components\TextInput::make('nik')
                                            ->required()
                                            ->maxLength(16),
                                        Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->required()
                                            ->maxLength(100),
                                        Forms\Components\TextInput::make('password')
                                            ->password()
                                            ->revealable(filament()->arePasswordsRevealable())
                                            ->rule(Password::default())
                                            ->autocomplete('new-password')
                                            ->dehydrated(fn($state): bool => filled($state))
                                            ->dehydrateStateUsing(fn($state): string => Hash::make($state))
                                            ->live(debounce: 500)
                                            ->same('passwordConfirmation'),
                                        Forms\Components\TextInput::make('passwordConfirmation')
                                            ->password()
                                            ->revealable(filament()->arePasswordsRevealable())
                                            ->required()
                                            ->visible(fn(Forms\Get $get): bool => filled($get('password')))
                                            ->dehydrated(false),
                                    ])->columns(2),
                                ]),
                            Forms\Components\Select::make('type')
                                ->options(MemberType::class)
                                ->required()
                                ->default(MemberType::Regular)
                                ->preload(),
                            Forms\Components\DateTimePicker::make('registered_at')
                                ->default(now())
                                ->seconds(false),
                            Forms\Components\DateTimePicker::make('interview_at')
                                ->seconds(false),
                        ])->columns(2),
                    Forms\Components\Section::make('Credentials')
                        ->relationship('user')
                        ->hiddenOn('create')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->minLength(3)
                                ->maxLength(200)
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('nik')
                                ->required()
                                ->maxLength(16),
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->required()
                                ->maxLength(100),
                            Forms\Components\TextInput::make('phone')
                                ->tel()
                                ->maxLength(15),
                            Forms\Components\TextInput::make('password')
                                ->password()
                                ->revealable(filament()->arePasswordsRevealable())
                                ->rule(Password::default())
                                ->autocomplete('new-password')
                                ->dehydrated(fn($state): bool => filled($state))
                                ->dehydrateStateUsing(fn($state): string => Hash::make($state))
                                ->live(debounce: 500)
                                ->same('passwordConfirmation'),
                            Forms\Components\TextInput::make('passwordConfirmation')
                                ->password()
                                ->revealable(filament()->arePasswordsRevealable())
                                ->required()
                                ->visible(fn(Forms\Get $get): bool => filled($get('password')))
                                ->dehydrated(false),
                        ])->columns(2),
                    Forms\Components\Section::make('Details')
                        ->relationship('profile')
                        ->hiddenOn('create')
                        ->schema([
                            Forms\Components\TextInput::make('nickname')
                                ->maxLength(100),
                            Forms\Components\ToggleButtons::make('gender')
                                ->inline()
                                ->options(Gender::class),
                            Forms\Components\TextInput::make('pob')
                                ->label('Place of birth'),
                            Forms\Components\DatePicker::make('dob')
                                ->label('Date of birth'),
                            Forms\Components\Select::make('marrital')
                                ->options(Marrital::class),
                            Forms\Components\Select::make('religion')
                                ->options(Religion::class),
                            Forms\Components\TextInput::make('instance')
                                ->maxLength(100),
                            Forms\Components\TextInput::make('faculty')
                                ->maxLength(100),
                            Forms\Components\TextInput::make('major')
                                ->maxLength(100),
                            Forms\Components\TextInput::make('nim')
                                ->numeric()
                                ->maxLength(30),
                            Forms\Components\TextInput::make('last_education')
                                ->maxLength(100),
                            Forms\Components\TextArea::make('address'),
                        ])->columns(2),
                    Forms\Components\Section::make('Activity')
                        ->relationship('profile')
                        ->hiddenOn('create')
                        ->schema([
                            Forms\Components\KeyValue::make('meta.activity')
                                ->keyLabel('Activity')
                                ->valueLabel('Position')
                                ->addable(),
                        ])
                ])->columnSpan(2),
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\ToggleButtons::make('recruitment_status')
                            ->options(RecruitmentStatus::class)
                            ->default(RecruitmentStatus::Submitted)
                            ->inline()
                            ->required(),
                    ]),
                    Forms\Components\Section::make([
                        Forms\Components\FileUpload::make('avatar')
                            ->image()
                            ->maxSize(1024)
                            ->label('Profile photo'),
                    ])->relationship('user')
                        ->hiddenOn('create'),
                    AppComponents\Forms\TimestampPlaceholder::make()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                AppComponents\Columns\IDColumn::make(),
                Tables\Columns\TextColumn::make('user.name')
                    ->wrap()
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                AppComponents\Columns\WhatsappLinkColumn::make('user.phone')
                    ->label('Whatsapp')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->sortable()
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('interview_at')
                    ->sortable(),
                Tables\Columns\TextColumn::make('recruitment_status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                AppComponents\Columns\LastModifiedColumn::make(),
                AppComponents\Columns\CreatedAtColumn::make(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('type')
                    ->options(MemberType::class),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-s-check')
                    ->button()
                    ->outlined()
                    ->hidden(fn(Member $record) => $record->recruitment_status === RecruitmentStatus::Approved)
                    ->action(function (?Member $record) {
                        $record->update([
                            'recruitment_status' => RecruitmentStatus::Approved,
                            'status' => true,
                            'joined_at' => now(),
                        ]);
                    }),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
            'index' => Pages\ListCandidates::route('/'),
            'create' => Pages\CreateCandidate::route('/create'),
            'edit' => Pages\EditCandidate::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->candidate()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
