<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class OferteInAsteptareReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param \Illuminate\Support\Collection<int, array<string, mixed>> $oferte
     */
    public function __construct(
        public Collection $oferte
    ) {
    }

    public function envelope(): Envelope
    {
        $count = $this->oferte->count();

        return new Envelope(
            subject: 'Reminder oferte in asteptare - ' . $count . ' ' . ($count === 1 ? 'oferta' : 'oferte'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emailuri.oferte.oferteInAsteptareReminder',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
