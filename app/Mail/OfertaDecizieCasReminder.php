<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Models\Incasare;

class OfertaDecizieCasReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Incasare $decizieCas,
        public string $tipReminder
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $pacient = $this->decizieCas->oferta->fisaCaz->pacient;
        $numePacient = trim(($pacient->nume ?? '') . ' ' . ($pacient->prenume ?? ''));

        return new Envelope(
            subject: 'Reminder decizie CAS - ' . ($numePacient ?: 'pacient fără nume') . ' (' . $this->tipReminder . ')',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emailuri.oferte.decizieCasReminder',
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
