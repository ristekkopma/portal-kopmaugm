<?php

namespace App\Filament\Portal\Pages\Auth;

use App\Enums\UserRole;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Notification;
use App\Models\User;
use App\Notifications\NewUserRegisteredNotification;
use Illuminate\Support\Facades\Notification as MailNotification;
use Illuminate\Support\Facades\Log;
use App\Services\WahaClient;
use App\Notifications\SystemActivityNotification;

class Register extends Page
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                Forms\Components\TextInput::make('phone')
                    ->label('Nomor Telepon')
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
        // 🔎 Cek duplikat nama, telepon, dan email
        if (User::where('name', $data['name'])->exists()) {
            throw ValidationException::withMessages(['name' => 'Nama sudah terdaftar.']);
        }

        if (User::where('phone', $data['phone'])->exists()) {
            throw ValidationException::withMessages(['phone' => 'Nomor telepon sudah terdaftar.']);
        }

        if (User::where('email', $data['email'])->exists()) {
            throw ValidationException::withMessages(['email' => 'Email sudah terdaftar.']);
        }

        // ⚙️ Set default role & verifikasi admin
        $data['role'] = UserRole::Candidate;
        $data['is_verified'] = false;

        // 💾 Simpan user baru
        $user = $this->getUserModel()::create($data);

        // 📨 Kirim notifikasi email ke semua superadmin
        $superadmins = User::where('role', 'super_admin')->get();
        MailNotification::send($superadmins, new NewUserRegisteredNotification($user));

        // setelah simpan user baru
        $superadmins = User::where('role', 'super_admin')->get();

        foreach ($superadmins as $admin) {
            $admin->notify(new SystemActivityNotification(
                title: 'Pendaftar Baru',
                message: "{$user->name} baru saja mendaftar di Portal Kopma UGM.",
                url: url('/admin/users')
            ));
        }

        // 💬 Kirim notifikasi WhatsApp via WahaClient instance
        try {
            $waha = new WahaClient(); // ✅ buat instance baru

            foreach ($superadmins as $admin) {
                if (! empty($admin->phone)) {
                    $chatId = $waha->toChatId($admin->phone); // ✅ ubah ke format 62xxxx@c.us
                    $message = "Halo {$admin->name}, ada pendaftar baru di Portal Kopma UGM:\n\n"
                        . "Nama: {$user->name}\n"
                        . "Email: {$user->email}\n"
                        . "Nomor HP: {$user->phone}\n"
                        . "Tanggal: " . now()->format('d-m-Y H:i') . "\n\n"
                        . "Segera lakukan verifikasi di dashboard admin.";

                    $response = $waha->sendText($chatId, $message);

                    if (! $response['ok']) {
                        Log::error('Gagal kirim notifikasi WA ke ' . $admin->phone, $response);
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim notifikasi WhatsApp: ' . $e->getMessage());
        }

        foreach ($superadmins as $admin) {
        $admin->notify(new SystemActivityNotification(
        title: 'Pendaftar Baru',
        message: "{$user->name} baru saja mendaftar di Portal Kopma UGM.",
        url: url('/admin/users')
    )); }

        // ✅ Notifikasi sukses untuk user
        Notification::make()
            ->title('Pendaftaran Berhasil')
            ->body('Akun Anda telah terdaftar. Silakan hubungi admin untuk verifikasi sebelum login.')
            ->success()
            ->send();

        return $user;
    }

    

}
