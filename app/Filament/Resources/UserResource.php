<?php

namespace App\Filament\Resources;

use App\Enums\UserRole;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\FormsComponent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Password;
use App\Filament\Components as AppComponents;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'User';
    protected static ?string $navigationLabel = 'System';
    protected static ?string $navigationGroupShort = '6';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->minLength(3)
                            ->maxLength(200)
                            ->extraAttributes(['class' => 'uppercase'])
                            ->mutateDehydratedStateUsing(fn ($state) => strtoupper($state)),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(100),
                    ]),
                    Forms\Components\Section::make([
                        Forms\Components\TextInput::make('phone')
                            ->required()
                            ->tel()
                            ->maxLength(15),
                    ]),
                    Forms\Components\Section::make()
                        ->columns(2)
                        ->schema([
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
                        ]),
                    Forms\Components\Section::make([
                        Forms\Components\Select::make('role')
                            ->options(UserRole::class)
                            ->preload()
                    ])
                ])->columnSpan(2),
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\DateTimePicker::make('email_verified_at'),
                    ]),
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
            Tables\Columns\TextColumn::make('name')
                ->translateLabel()
                ->searchable(),
            AppComponents\Columns\WhatsappLinkColumn::make(),
            Tables\Columns\TextColumn::make('email')
                ->searchable(),
            Tables\Columns\TextColumn::make('role')
                ->badge()
                ->searchable(),

           
            AppComponents\Columns\LastModifiedColumn::make(),
            AppComponents\Columns\CreatedAtColumn::make(),
        ])
        ->filters([
            //
        ])
        ->actions([
    

    // 🔹 Kolom gabungan: tombol Verifikasi / ikon centang
    Tables\Actions\Action::make('verifikasi')
        ->label(fn ($record) => $record->is_verified ? 'Done' : 'Check')
        ->icon(fn ($record) => $record->is_verified ? 'heroicon-o-check-circle' : 'heroicon-o-clock')
        ->color(fn ($record) => $record->is_verified ? 'success' : 'warning')
        ->disabled(fn ($record) => $record->is_verified) // kalau sudah diverifikasi, nonaktifkan tombol
        ->requiresConfirmation(fn ($record) => ! $record->is_verified)
        ->visible(fn ($record) => true) // tampil selalu
        ->action(function ($record) {
            if (! $record->is_verified) {
                $record->update(['is_verified' => true]);

                \Filament\Notifications\Notification::make()
                    ->title('User berhasil diverifikasi')
                    ->body("Akun {$record->name} sekarang sudah aktif dan dapat login.")
                    ->success()
                    ->send();
            }
        }),
        Tables\Actions\EditAction::make(),

   ])

   
;

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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
