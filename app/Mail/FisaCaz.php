<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FisaCaz extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public \App\Models\FisaCaz $fisaCaz, public $tipEmail, public $mesaj, public \App\Models\User $user)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: (($this->tipEmail == "fisaCaz") ? 'Fișă caz' : (($this->tipEmail == "oferta") ? 'Ofertă' : (($this->tipEmail == "comanda") ? 'Fișă comandă' : ''))) .
            ' pacient ' . ($this->fisaCaz->pacient->nume ?? '') . ' ' . ($this->fisaCaz->pacient->prenume ?? '') . ' - proteză ' . ($this->fisaCaz->dateMedicale()->first->tip_proteza ?? ''),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emailuri.fiseCaz.fisaCaz',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
