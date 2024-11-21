<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Enums\Gender;
use App\Models\Member;
use App\Models\Wallet;
use App\Enums\Marrital;
use App\Enums\Religion;
use Filament\Forms\Form;
use App\Enums\MemberType;
use App\Models\Candidate;
use Filament\Tables\Table;
use App\Enums\MemberStatus;
use Illuminate\Support\Str;
use App\Enums\CandidateStatus;
use App\Enums\RecruitmentStatus;
use App\Enums\UserRole;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Password;
use App\Filament\Components as AppComponents;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\CandidateResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CandidateResource\RelationManagers;

class CandidateResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('Candidate');
    }

    public static function getNavigationGroup(): ?string
    {
        return __(__('Membership'));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make(__('Membership'))
                        ->schema([
                            Forms\Components\Select::make('user_id')
                                ->relationship('user', 'name', fn(Builder $query) => $query->whereDoesntHave('member'))
                                ->preload()
                                ->placeholder(__('Select user or create new'))
                                ->required()
                                ->createOptionForm([
                                    Forms\Components\Group::make([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->minLength(3)
                                            ->maxLength(200),
                                        Forms\Components\TextInput::make('nik')
                                            ->label('NIK')
                                            ->unique(ignoreRecord: true)
                                            ->numeric()
                                            ->rules(['digits:16'])
                                            ->required()
                                            ->live(onBlur: true)
                                            ->hint(fn($state) => 'Currently ' . strlen($state) . ' digits.'),
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
                                ->seconds(false)
                                ->hiddenOn('create'),
                        ])->columns(2),
                    Forms\Components\Section::make(__('Credentials'))
                        ->relationship('user')
                        ->hiddenOn('create')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->minLength(3)
                                ->maxLength(200)
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('nik')
                                ->label('NIK')
                                ->unique(ignoreRecord: true)
                                ->numeric()
                                ->rules(['digits:16'])
                                ->required()
                                ->live(onBlur: true)
                                ->hint(fn($state) => 'Currently ' . strlen($state) . ' digits.'),
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
                            Forms\Components\Textarea::make('address'),
                        ])->columns(2),
                    Forms\Components\Section::make(__('Activity'))
                        ->relationship('profile')
                        ->hiddenOn('create')
                        ->schema([
                            Forms\Components\KeyValue::make('meta.activity')
                                ->label(false)
                                ->keyLabel(__('Activity'))
                                ->valueLabel(__('Position'))
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
            ->defaultSort('created_at', 'desc')
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
                    ->requiresConfirmation()
                    ->hidden(fn(Member $record) => $record->recruitment_status === RecruitmentStatus::Approved)
                    ->action(function (?Member $record) {
                        $record->update([
                            'code' => Str::random(8),
                            'recruitment_status' => RecruitmentStatus::Approved,
                            'status' => true,
                            'joined_at' => now(),
                        ]);
                        $record->user->role = UserRole::Member;
                        $record->user->save();

                        Wallet::create([
                            'user_id' => $record->user_id
                        ]);
                        Notification::make('Candidate has been approved')->success()->send();
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
