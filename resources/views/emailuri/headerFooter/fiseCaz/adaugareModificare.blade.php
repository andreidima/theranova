<div style="margin:0 auto;width:100%; background-color:#eff1f0;">
    <div style="margin:0 auto; max-width:800px!important; background-color: white;">

        @include ('emailuri.headerFooter.header')

        <div style="padding:20px 20px; max-width:760px!important;margin:0 auto; font-size:18px">
            {{-- Bună {{ $tombola->nume }},
            <br><br>
            Te-ai înscris la Tombola pentru topul „{{ $tombola->top }}”.
            <br>
            Codul tău este: <span style="font-weight: bold; font-size:200%">{{ $tombola->cod }}</span> --}}

            <br><br><br>
            Acesta este un mesaj automat. Te rugăm să nu răspunzi la acest e-mail.
            <br><br>
            Mulțumim!
        </div>
    </div>

    @include ('emailuri.headerFooter.footer')
</div>

