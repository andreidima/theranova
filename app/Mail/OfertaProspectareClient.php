<?php

namespace App\Mail;

use App\Models\OfertaProspectare;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OfertaProspectareClient extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public OfertaProspectare $oferta,
        public ?string $mesaj = null
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Oferta Theranova #' . $this->oferta->id
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emailuri.oferteProspectare.client'
        );
    }

    /**
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $oferta = $this->oferta->loadMissing(['emitent', 'aprobator', 'linii']);
        $pdf = \PDF::loadView('oferteProspectare.export.pdf', ['oferta' => $oferta])
            ->setPaper('a4', 'portrait');
        $pdf->getDomPDF()->set_option('enable_php', true);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'oferta-theranova-' . $oferta->id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
