<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use App\Services\WahaClient;
use App\Notifications\Channels\WhatsappChannel;
use Illuminate\Support\Facades\Log;

class NewUserRegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $newUser;

    public function __construct(User $newUser)
    {
        $this->newUser = $newUser;
    }

    /**
     * Tentukan channel notifikasi yang digunakan
     */
    public function via($notifiable)
    {
        // Kirim lewat email + custom WhatsApp
        return ['mail', WhatsappChannel::class];
    }

    /**
     * Notifikasi Email
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🆕 Pendaftar Baru di Portal Kopma UGM')
            ->greeting('Halo Superadmin 👋')
            ->line('Ada pengguna baru yang mendaftar ke portal Kopma UGM.')
            ->line('Nama: ' . $this->newUser->name)
            ->line('Email: ' . $this->newUser->email)
            ->line('Nomor HP: ' . $this->newUser->phone)
            ->line('Tanggal Daftar: ' . now()->format('d M Y H:i'))
            ->action('Lihat di Dashboard', url('/admin'))
            ->line('Segera lakukan verifikasi jika data sesuai.');
    }

    /**
     * Notifikasi WhatsApp (custom channel)
     */
    public function toWhatsapp($notifiable)
    {
        if (! empty($notifiable->phone)) {
            $waha = new WahaClient(); // ✅ gunakan instance
            $chatId = $waha->toChatId($notifiable->phone);

            $message = "Halo {$notifiable->name}, ada pendaftar baru di Portal Kopma UGM:\n\n"
                . "Nama: {$this->newUser->name}\n"
                . "Email: {$this->newUser->email}\n"
                . "No HP: {$this->newUser->phone}\n"
                . "Tanggal: " . now()->format('d-m-Y H:i') . "\n\n"
                . "Segera lakukan verifikasi di dashboard admin.";

            $response = $waha->sendText($chatId, $message);

            if (! $response['ok']) {
                Log::error('Gagal kirim notifikasi WhatsApp ke ' . $notifiable->phone, $response);
            }
        }
    }
}
