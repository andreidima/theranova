<div style="margin:0 auto;width:100%; background-color:#eff1f0;">
    <div style="margin:0 auto; max-width:800px!important; background-color: white;">

        @include ('emailuri.headerFooter.header')

        <div style="padding:20px 20px; max-width:760px!important;margin:0 auto; font-size:18px">
            Buna,

            <br><br>

            @if ($oferte->count() === 1)
                Au trecut 3 luni de la incarcarea acestei oferte, iar statusul ei este in continuare „In asteptare”.
            @else
                Au trecut 3 luni de la incarcarea acestor oferte, iar statusul lor este in continuare „In asteptare”.
            @endif

            <br><br>

            @foreach ($oferte as $ofertaInfo)
                <div style="margin-bottom: 14px;">
                    <a href="{{ $ofertaInfo['link_modificare'] }}" target="_blank">
                        Oferta #{{ $ofertaInfo['id'] }} - {{ $ofertaInfo['pacient'] }}
                    </a>
                    <br>
                    Creata la: {{ $ofertaInfo['created_at'] }} | Vechime: {{ $ofertaInfo['vechime_zile'] }} zile
                </div>
            @endforeach

            <br>

            @if ($oferte->count() === 1)
                Pentru a mentine situatiile la zi, este necesar sa fie luata o decizie privind aceasta oferta.
            @else
                Pentru a mentine situatiile la zi, este necesar sa fie luata o decizie privind aceste oferte.
            @endif
            Te rugam sa actualizezi statusul, alegand una dintre optiunile disponibile:

            <br><br>

            Acceptata
            <br>
            Respinsa (NU)
            <br>
            Arhivata

            <br><br>

            Iti multumim pentru promptitudine.

            <br><br><br>
            Acesta este un mesaj trimis automat din aplicatia Theranova. Te rugam sa nu raspunzi la acest e-mail.
            <br><br>
            Multumim!
        </div>
    </div>

    @include ('emailuri.headerFooter.footer')
</div>
