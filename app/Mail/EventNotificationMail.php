<?php

namespace App\Mail;

use App\Models\EventNotificationRecipient;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public EventNotificationRecipient $recipient) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Event Terbaru Kopma UGM: '.$this->recipient->event->title);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.event-notification');
    }
}
