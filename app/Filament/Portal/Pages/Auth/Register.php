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

        try {
        return $this->getUserModel()::create($data);
    } catch (QueryException $e) {
        if ($e->getCode() === '23000') {
            Notification::make()
                ->title('Pendaftaran Gagal')
                ->body('Nomor HP atau email sudah digunakan. Silakan gunakan yang lain.')
                ->danger()
                ->persistent() // agar tidak hilang otomatis
                ->send();
        }

        throw $e; // tetap lempar ulang error lain yang tidak ditangani
    }
    }
}
