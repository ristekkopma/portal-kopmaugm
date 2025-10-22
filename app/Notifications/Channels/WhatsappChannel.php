<?php

namespace App\Notifications\Channels;

class WhatsappChannel
{
    public function send($notifiable, $notification)
    {
        if (method_exists($notification, 'toWhatsapp')) {
            return $notification->toWhatsapp($notifiable);
        }
    }
}
