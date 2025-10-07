<div style="margin:0 auto;width:100%; background-color:#eff1f0;">
    <div style="margin:0 auto; max-width:800px!important; background-color: white;">

        @include ('emailuri.headerFooter.header')

        <div style="padding:20px 20px; max-width:760px!important;margin:0 auto; font-size:18px">
            Bună,

            <br><br>

            <p style="margin:0 0 16px 0;">
                Pentru oferta
                <a href="{{ url($decizieCas->oferta->path()) }}/modifica" target="_blank">#{{ $decizieCas->oferta->id }}</a>
                asociată fișei de caz a pacientului
                {{ $decizieCas->oferta->fisaCaz->pacient->nume ?? '' }} {{ $decizieCas->oferta->fisaCaz->pacient->prenume ?? '' }},
                decizia CAS înregistrată la data de {{ $decizieCas->data_inregistrare }} nu are încă completată o dată de validare.
            </p>

            <p style="margin:0 0 16px 0;">
                Acesta este {{ $tipReminder }} pentru completarea informațiilor. Vă rugăm să verificați situația și să actualizați
                câmpul „Data validare” dacă decizia a fost deja validată.
            </p>

            <p style="margin:0 0 16px 0;">
                Mulțumim!<br>
                Aplicația Theranova
            </p>

            <p style="font-size:14px; color:#6c757d; margin:24px 0 0 0;">
                Acesta este un mesaj trimis automat. Te rugăm să nu răspunzi la acest e-mail.
            </p>
        </div>
    </div>

    @include ('emailuri.headerFooter.footer')
</div>
