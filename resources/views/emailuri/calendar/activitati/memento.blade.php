<div style="margin:0 auto;width:100%; background-color:#eff1f0;">
    <div style="margin:0 auto; max-width:800px!important; background-color: white;">

        @include ('emailuri.headerFooter.header')

        <div style="padding:20px 20px; max-width:760px!important;margin:0 auto; font-size:18px">
            {{-- Bună {{ $userName ?? '' }}, --}}
            Bună,
            <br><br>
            Activitate calendar: <a href="{{ url($activitate->path()) }}" target="_blank">{{ $activitate->descriere }}</a>
            @if ($activitate->fisaCaz)
                <br><br>
                <a href="{{ url($activitate->fisaCaz->path() ?? '') }}" target="_blank">Fișa caz</a> a pacientului {{ $activitate->fisaCaz->pacient->nume ?? '' }} {{ $activitate->fisaCaz->pacient->prenume ?? '' }}
            @endif
            <br><br><br>
            Acesta este un mesaj trimis direct din aplicația Theranova. Te rugăm să nu răspunzi la acest e-mail.
            <br><br>
            Mulțumim!
        </div>
    </div>

    @include ('emailuri.headerFooter.footer')
</div>

