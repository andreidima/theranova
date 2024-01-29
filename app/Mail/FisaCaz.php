<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Illuminate\Mail\Mailables\Attachment;

class FisaCaz extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public \App\Models\FisaCaz $fisaCaz, public $tipEmail, public $mesaj = null, public $userName = null)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: (($this->tipEmail == "fisaCaz") ? 'Fișă caz' : (($this->tipEmail == "oferta") ? 'Ofertă' : (($this->tipEmail == "comanda") ? 'Fișă comandă' : $this->tipEmail))) .
            ' - pacient ' . ($this->fisaCaz->pacient->nume ?? '') . ' ' . ($this->fisaCaz->pacient->prenume ?? '') . ' - proteză ' . ($this->fisaCaz->dateMedicale->first()->tip_proteza ?? ''),
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
        $fisaCaz = $this->fisaCaz;

        $arrayFisiere = [];

        if (($this->tipEmail == "oferta") && ($fisaCaz->oferte->count() > 0)) {
            foreach ($fisaCaz->oferte as $oferta) {
                foreach ($oferta->fisiere as $fisier){
                    array_push($arrayFisiere, Attachment::fromStorage($fisier->cale . '/' . $fisier->nume));
                }
            }
        } elseif ($this->tipEmail == "Comanda sosită") {
            if ($fisaCaz->fisiereComanda->count() > 0) {
                foreach ($fisaCaz->fisiereComanda as $fisier) {
                    array_push($arrayFisiere, Attachment::fromStorage($fisier->cale . '/' . $fisier->nume));
                }
            }

            if ($fisaCaz->comenziComponente->count() > 0) {
                $pdf = \PDF::loadView('comenziComponente.toate.export.comandaComponentePdf', compact('fisaCaz'))
                    ->setPaper('a4', 'portrait');
                $pdf->getDomPDF()->set_option("enable_php", true);

                array_push($arrayFisiere, Attachment::fromData(fn () => $pdf->output(), 'Fișă comandă ' . ($this->fisaCaz->pacient->nume ?? '') . ' ' . ($this->fisaCaz->pacient->prenume ?? '') . '.pdf'));
            }
        }

        return $arrayFisiere;
    }
}
