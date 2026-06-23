@include('emailuri.headerFooter.header')

<p>Buna ziua,</p>

@if($mesaj)
    <p>{!! nl2br(e($mesaj)) !!}</p>
@else
    <p>Va transmitem atasat oferta Theranova #{{ $oferta->id }}.</p>
@endif

<p>
    Valoare oferta: <b>{{ number_format((int) $oferta->valoare_totala, 0, ',', '.') }} lei</b><br>
    Valabila pana la: <b>{{ optional($oferta->valabila_pana_la)->format('d.m.Y') }}</b>
</p>

<p>Va multumim.</p>

@include('emailuri.headerFooter.footer')
