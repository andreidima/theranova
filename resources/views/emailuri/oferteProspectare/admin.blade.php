@include('emailuri.headerFooter.header')

<p>{{ $subiect }}</p>

<p>
    Oferta prospectare #{{ $oferta->id }}<br>
    Client: <b>{{ $oferta->nume_client }}</b><br>
    Telefon: {{ $oferta->telefon }}<br>
    Valoare: {{ number_format((int) $oferta->valoare_totala, 0, ',', '.') }} lei
</p>

<p>
    Link aplicatie: <a href="{{ url($oferta->path()) }}">{{ url($oferta->path()) }}</a>
</p>

@include('emailuri.headerFooter.footer')
