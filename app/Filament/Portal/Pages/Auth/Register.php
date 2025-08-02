<?php

namespace App\Filament\Portal\Pages\Auth;

use App\Enums\MemberType;
use App\Enums\UserRole;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Filament\Notifications\Notification;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class Register extends Page
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->tel()
                    ->placeholder('Contoh: 081232456788')
                    ->maxLength(15),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function handleRegistration(array $data): Model
    {
          // Cek duplikat
        if (User::where('name', $data['name'])->exists()) {
            throw ValidationException::withMessages([
                'name' => 'Nama sudah terdaftar.',
            ]);
        }

        if (User::where('phone', $data['phone'])->exists()) {
            throw ValidationException::withMessages([
                'phone' => 'Nomor telepon sudah terdaftar.',
            ]);
        }

        if (User::where('email', $data['email'])->exists()) {
            throw ValidationException::withMessages([
                'email' => 'Email sudah terdaftar.',
            ]);
        }

        // Lanjut buat user
        $data['role'] = UserRole::Candidate;

        return $this->getUserModel()::create($data);
    }
}
