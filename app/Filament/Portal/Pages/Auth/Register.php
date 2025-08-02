<?php

namespace App\Filament\Portal\Pages\Auth;

use App\Enums\MemberType;
use App\Enums\UserRole;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as Page;
use Illuminate\Database\Eloquent\Model;

class Register extends Page
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                // Forms\Components\TextInput::make('nik')
                //     ->label('NIK')
                //     ->unique(ignoreRecord: true)
                //     ->numeric()
                //     ->rules(['digits:16'])
                //     ->required()
                //     ->live(onBlur: true)
                //     ->hint(fn($state) => __('Currently') . ' ' . strlen($state) . ' digits.'),
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->tel()
                     ->unique(
        table: 'users',
        column: 'phone',
        message: 'Nomor HP ini sudah digunakan. Silakan gunakan yang lain.'
    )
                    ->maxLength(15),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function handleRegistration(array $data): Model
    {
        $data['role'] = UserRole::Candidate;

        return $this->getUserModel()::create($data);
    }
}
