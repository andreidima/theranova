<?php

namespace App\Mail;

use App\Models\OfertaProspectare;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OfertaProspectareAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public OfertaProspectare $oferta,
        public string $subiect
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subiect . ' - oferta prospectare #' . $this->oferta->id
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emailuri.oferteProspectare.admin'
        );
    }
}
