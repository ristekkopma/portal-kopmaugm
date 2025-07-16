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
                Forms\Components\TextInput::make('nik')
                    ->label('NIK')
                    ->unique(ignoreRecord: true)
                    ->numeric()
                    ->rules(['digits:1'])
                    ->required()
                    ->live(onBlur: true)
                    ->hint(fn($state) => __('Currently') . ' ' . strlen($state) . ' digits.'),
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
