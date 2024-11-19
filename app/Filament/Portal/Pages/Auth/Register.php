<?php

namespace App\Filament\Portal\Pages\Auth;

use App\Enums\MemberType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as Page;

class Register extends Page
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                Forms\Components\TextInput::make('nik')
                    ->label('NIK')
                    ->required()
                    ->maxLength(16),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
