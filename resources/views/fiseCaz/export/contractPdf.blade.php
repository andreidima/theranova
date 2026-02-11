@php
    use Carbon\Carbon;
@endphp

<!DOCTYPE  html>
<html lang="ro">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Contract</title>
    <style>
        /* html {
            margin: 0px 0px;
        } */
        /** Define the margins of your page **/
        @page {
            margin: 0px 0px;
        }

        header {
            position: fixed;
            top: 20px;
            left: 0px;
            right: 0px;
            height: 250px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            /* font-family: Arial, Helvetica, sans-serif; */
            font-size: 12px;
            /* margin-top: 1cm; */
            margin-top: 4cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
        }

        * {
            /* padding: 0; */
            text-indent: 0;
            text-align: justify;
        }

        table{
            border-collapse:collapse;
            margin: 0px;
            padding: 5px;
            margin-top: 0px;
            border-style: solid;
            border-width: 0px;
            width: 100%;
            word-wrap:break-word;
        }

        th, td {
            padding: 1px 10px;
            border-width: 0px;
            border-style: solid;

        }
        tr {
            border-style: solid;
            border-width: 0px;
        }
        hr {
            display: block;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
            margin-left: auto;
            margin-right: auto;
            border-style: inset;
            border-width: 0.5px;
        }
    </style>
</head>

<body>
    <header style="margin:0px 0px 0px 0px; text-align: center;">
        <img src="{{ asset('images/logo2-400x103.jpg') }}" width="400px">
    </header>

    <main style="
            background-image: url('{{ asset('images/contractBackground.jpg') }}');
            background-size: 100%;
            background-repeat: no-repeat;">

        {{-- <div style="page-break-after: always"> --}}
        <p style="font-size:150%; text-align: center;">CONTRACT DE PRESTĂRI SERVICII</p>
        <p style="font-size:120%; text-align: center;">
            Nr. {{ $fisaCaz->ofertaAcceptata->contract_nr ?? '________' }}
            din data de {{ ($fisaCaz->ofertaAcceptata->contract_data ?? null) ? Carbon::parse($fisaCaz->ofertaAcceptata->contract_data)->isoFormat('DD.MM.YYYY') : '_________________' }}
        </p>

        <br>
        <br>
        <p><b>1. Părțile contractante</b>:</p>
        <p><b>1.1. S.C. THERANOVA PROTEZARE S.R.L.</b>, denumit în continuare furnizor de servicii, cu sediul în Oradea, str. Eroul Necunoscut, nr. 2 judeţul Bihor, înregistrat la ORC Bihor sub nr. J05/1172/11.09.2003, codul de înregistrare fiscală RO 15736030, reprezentat prin Jaco du Plessis, în calitate de administrator</p>
        <p>
            <b>1.2 {{ ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') }}</b>, denumit în continuare beneficiar, domiciliat în localitatea {{ $fisaCaz->pacient->localitate ?? '' }}, {{ $fisaCaz->pacient->adresa ?? '' }} , judeţul/ sectorul {{ $fisaCaz->pacient->judet ?? '' }}, codul numeric personal {{ $fisaCaz->pacient->cnp ?? '' }},
            posesor al B.I./C.I. seria {{ substr(($fisaCaz->pacient->serie_numar_buletin ?? ''), 0, 2) }}, nr. {{ preg_replace('/[^0-9]/', '', ($fisaCaz->pacient->serie_numar_buletin)) }},
            eliberat/eliberată la data de {{ $fisaCaz->pacient->data_eliberare_buletin ? Carbon::parse($fisaCaz->pacient->data_eliberare_buletin)->isoFormat('DD.MM.YYYY') : '' }}.
        </p>
        <br>

        <p><b>2. Obiectul contractului</b> constă în realizarea unui act de protezare/ortezare sub forma confecţionării <b>{{ $fisaCaz->ofertaAcceptata->obiect_contract ?? '' }}</b>, pentru beneficiar, în condiţiile şi la preţul stabilit în prezentul act juridic.</p>


        <p>
            <b>3. Durata contractului</b> se stabilește de comun acord cu beneficiarul.
            <br>
            <b>3.1.</b> Durata contractului poate fi prelungită prin act adiţional, cu acordul părţilor.
        </p>

        <b>4. Preţul</b> stabilit pentru furnizarea serviciilor de protezare este de <b>{{ $fisaCaz->ofertaAcceptata->pret ?? '' }} lei</b>.
        <br>
        S.C. THERANOVA PROTEZARE S.R.L. nu își asumă răspunderea pentru modificarea prețurilor practicate de către producător.
        <br>
        <br>

        <b>5. Etapele furnizării serviciilor de protezare</b>
        <br>
        <b>5.1. Redactarea ofertei de preţ</b>
        <br>
        Oferta de preţ este un material informativ redactat de <b>S.C. THERANOVA PROTEZARE S.R.L.</b>, în calitate de furnizor de servicii de protezare, care conţine detalii referitoare la producătorul, calitatea, cantitatea şi preţul viitoarei proteze/orteze.
        <br>
        Documentul este valabil 10 zile lucrătoare şi nu generează drepturi sau obligaţii în favoarea/sarcina nici uneia dintre părţi. Oferta de preţ reprezintă parte integrantă a prezentului contract - <b>anexa nr. 2</b>.
        <br>
        <b>5.2. Transmiterea notei de comandă</b>
        <br>
        Nota de comandă este un document semnat şi transmis furnizorului de servicii de protezare/ortezare de către beneficiar,  în concordanţă cu informaţiile din cuprinsul ofertei de preţ. Transmiterea notei de comandă reprezintă acordul de voinţă al beneficiarului cu privire la contractarea serviciilor furnizorului de proteze/orteze. Nota de comandă reprezintă parte integrantă a ofertei de preț - <b>anexa nr. 2</b>.
        <br>
        <b>5.3. Completarea fişei individuale de măsurători</b>
        <br>
        În baza notei de comandă transmise de către beneficiar se efectuează măsurători în vederea completării datelor tehnice necesare confecţionării protezei provizorii sau definitive. Fişa individuală de măsurători este parte integrantă a prezentului contract - <b>anexa nr. 3</b>.
        <br>
        <b>5.4. Condiţii de plată</b>
        <br>
        Indiferent de varianta de achiziție pentru care optează beneficiarul - plata integrală sau prin Casa de Asigurări de Sănătate beneficiarul va achita furnizorului contravaloarea dispozitivului medical după cum urmează:
        <ul style="margin: 0px">
            <li>la deschiderea comenzii va achita un avans de 70% din valoarea dispozitivelor medicale (cei care dețin o decizie eliberată de cate CAS a cărei valoare nu acoperă integral cuantumul avansului  vor trebui sa achite diferența),</li>
            <li>20% la predarea primei proteze/orteze intermediare</li>
            <li>10% la livrarea protezei/ortezei finale.</li>
        </ul>
        Plata se va face in contul SC Theranova Protezare SRL deschis la:
        <br>
        Banca Transilvania Sucursala Oradea, cont  <b>IBAN RON: RO71BTRL00501202765924XX</b>
        <br>
        <b>5.5. Predarea/primirea dispozitivelor medicale</b>
        <br>
        <b><i>Predarea protezei finale se va face in termen de maxim 9 luni de la predarea protezei intermediare.</i> Data predarii/primirii protezei finale se va stabili de comun acord cu beneficiarul in intervalul mentionat anterior la sediul furnizorului de servicii de protezare în intervalul orar de functionare a institutiei.</b>
        În cazul nerespectării datei stabilite de comun acord se va fixa o noua data in acest sens.
        Daca beneficiarul refuza sau nu se prezinta la data stabilita, furnizorului de servicii de protezare/ortezare nu-i va putea fi opusa nepredarea la termen a dispozitivelor medicale.
        <i>In masura in care exista posibilitatea transmiterii prin colet postal a dispozitivelor medicale clientului, furnizorul va proceda la transmiterea acestora, la solicitarea expresa a beneficiarului adresata prin comunicare scrisa/email cu confirmare de primire a solicitarii si a predării coletului</i>.
        Procesul verbal de predare-primire este însoţit de certificatul de garanţie al dispozitivului medical.
        <b>Termenul de garanţie privind execuția este de 1 luna pentru proteza provizorie - cupa diagnostic, respectiv de 24 luni pentru proteza definitivă</b>.
        Garantia de conformitate a produselor este de 24 luni. Termenul de garantie este de 24 luni pentru componentele structurale si manopera.
        <br>
        <b>Garanția se referă la manopera pentru executarea cupei protetice, iar pentru restul componentelor dispozitivului medical (labă protetică, tub, adaptoare, liner silicon, articulatie genunchi etc) garanția si termenul de garantie al respectivelor produse este asigurata de către producator si intra in vigoare din momentul in care sunt puse in folosinta adica predarea protezei provizorii cu componentele protetice achizitionate.</b>
        Garanţia pentru intreg dispozitivul medical nu este valabilă în cazul în care defecţiunea apare din culpa beneficiarului prin nerespectarea instrucţiunilor de manipulare, precum şi în cazurile în care reparaţiile se impun din pricina schimbării condiţiilor anatomice şi/sau fiziologice ale beneficiarului (exemplu modificări de volum ale bontului). Procesul verbal de predare primire - anexa 4 - şi certificatul de garanţie - <b>anexa 5</b> - sunt părţi integrante ale prezentului contract.
        <br>
        <br>

        <b>6. Drepturile părţilor</b>
        <br>
        <b>6.1. Furnizorul de servicii de protezare/ortezare are următoarele drepturi:</b>
        <br>
        a) Să beneficieze la termenele stabilite de plata avansului/preţului dispozitivelor medicale furnizate beneficiarului în conformitate cu nota de comandă transmisă de acesta şi a fişei individuale de măsurători;
        <br>
        b) Să beneficieze de returnarea protezei/ortezei provizorii în momentul finalizării şi predării către beneficiar a  protezei definitive (nereturnarea protezei provizorii implică cheltuieli suplimentare care nu sunt cuprinse in preț - peste valoarea ofertei);
        <br>
        c) Să beneficieze de acoperirea eventualelor prejudicii rezultate din culpa beneficiarului conform reglementărilor de la <b>pct. 8</b> ale prezentului contract
        <br>
        <b>6.2. Beneficiarul are următoarele drepturi:</b>
        <br>
        a) Să beneficieze de dispozitive medicale conform măsurătorilor consemnate în fişa individuală de măsurători semnată de acesta;
        <br>
        b) Să primească dispozitivele medicale la data stabilită de comun accord cu furnizorul de dispozitive medicale;
        <br>
        c) Să beneficieze de instrucţiuni corespunzătoare în vederea utilizării în condiţii optime a dispozitivelor medicale;
        <br>
        d) Să beneficieze cu titlu gratuit de service în perioada de garanţie a dispozitivelor medicale şi contra cost, ulterior expirării acestui termen.
        <br>
        <br>

        <b>7. Obligaţiile părţilor</b>
        <br>
        <b>7.1. Furnizorul de servicii de protezare/ortezare are următoarele obligaţii:</b>
        <br>
        a) Să confecţioneze dispozitivele medicale conform măsurătorilor consemnate în fişa de măsurători individuale semnată de beneficiar, fără a fi răspunzător de schimbările anatomice şi/sau fiziologice ale bontului apărute de la luarea mulajului până la predarea protezei;
        <br>
        b) Să predea dispozitivele medicale la data stabilită de comun acord cu beneficiarul dispozitivului medical prin proces-verbal semnat de ambele părți
        <br>
        c) Să acorde instrucţiunile necesare pentru folosirea optimă a dispozitivelor medicale;
        <br>
        d) Să asigure service cu titlu gratuit în  perioada de garanţie la sediul societăţii, conform programului zilnic de lucru;
        <br>
        e) Să asigure service contra cost după expirarea termenului de garanţie;
        <br>
        f) Tehnicianul se angajează sa recomande pacientului varianta optima de protezare, refuzul pacientului de a urma indicațiile consemnat prin proces-verbal absolvă de orice răspundere  Centrul de Protezare si Ortezare Theranova.
        <br>
        <br>

        <b>7.2. Beneficiarul are următoarele obligaţii:</b>
        <br>
        a) Să se prezinte la furnizorul de servicii în vederea protezării definitive/provizorii în termenele stabilite de comun accord;
        <br>
        b) Să depună toată diligenţa în vederea obţinerii deciziei care atestă cofinanţarea dispozitivului medical prin sistemul de asigurări sociale de sănătate, in cazul in care beneficiarul agreează această variantă de cofinanțare;
        <br>
        c) Să fie prezent la data stabilită de comun acord pentru predarea dispozitivului medical; în caz contrar furnizorul de servicii va proceda la transmiterea dispozitivului medical sub formă de colet poştal;
        <br>
        d) Să folosească şi să întreţină dispozitivele medicale conform instrucţiunilor furnizorului de servicii;
        <br>
        e) Să plătească in conformitate cu cele stipulate la pct. 5.4;
        <br>
        f) Să predea furnizorului de servicii proteza provizorie în momentul obţinerii protezei definitive;
        <br>
        g) Să prezinte certificatul de garanție în vederea înregistrării tuturor  intervențiilor care au loc în perioada de garanție;
        <br>
        h) Să informeze Centrul de Protezare și Ortezare Theranova în cel mai scurt timp de la data apariției, asupra problemelor pe care le identifică pe durata utilizării dispozitivului medical furnizat;
        <br>
        i) Să acopere eventualele prejudicii cauzate furnizorului de servicii din culpa sa conform reglementărilor de la pct. 8 ale prezentului contract;
        <br>
        j) In cazul in care pacientul dorește o anumita soluție de protezare, acesta isi va asuma răspunderea pentru eventualele prejudicii sau inconveniente ulterioare;
        <br>
        k) In cazul in care primeste spre folosinta, in perioada de proba sau pe perioada asigurarii service-ului, anumite componente protetice (picior protetic, articulatie genunchi, mana protetica i-Limb, mana protetica BeBionic etc), acesta este obligat sa le restituie, la finele perioadei respective, in aceeasi stare de functionare ca si cea initiala, orice pagube rezultate urmare a nerestituirii, utilizarii necorespunzatoare a acestora sau din orice alte motive imputabile acestuia, vor fi suportate integral de catre acesta.
        <br>
        <br>

        <b>8. Riscuri contractuale</b>
        <br>
        Beneficiarul răspunde pentru cheltuielile contractate de furnizorul de servicii în vederea confecţionării dispozitivelor medicale în cazul în care solicită rezilierea contractului. În astfel de situaţii, beneficiarul va fi obligat la plata integrală a sumelor investite de către furnizorul de servicii în vederea acoperirii tuturor cheltuielilor și a prejudiciului acestuia.
        <br>
        <br>

        <b>9. Încetarea contractului</b>
        <br>
        Constituie motiv de încetare a prezentului contract următoarele:
        <br>
        a) Expirarea duratei pentru care a fost încheiat contractul;
        <br>
        b) Acordul părţilor privind încetarea contractului;
        <br>
        c) Scopul contractului a fost atins;
        <br>
        d) Forţa majoră, dacă este invocată;
        <br>
        e) In caz de deces/desfiinţare a uneia din părţile contractante.
        <br>
        <br>

        <b>10. Dispoziţii finale</b>
        <br>
        Părţile contractante au dreptul, pe durata îndeplinirii prezentului contract, de a conveni modificarea clauzelor acestuia prin act adiţional numai în cazul apariţiei unor  circumstanţe care lezează interesele legitime ale acestora şi care nu au putut fi prevăzute la data încheierii prezentului contract . Prevederile prezentului contract se vor completa cu prevederile legislaţiei în vigoare în domeniu. Limba care guvernează prezentul contract este limba română. Prezentul contract va fi interpretat conform legilor din România. Eventualele litigii se vor soluţiona pe cale amiabilă iar în caz de neînţelegeri de către instanţele judecătoreşti competente de la sediul furnizorului de servicii.
        <br>
        <br>

        <b>Toate componentele folosite de S.C. THERANOVA PROTEZARE S.R.L. la confecționarea dispozitivelor medicale sunt certificate și sunt in concordanta cu normele și standardele UE.</b>
        <br>
        <b>Beneficiarul a fost informat referitor la posibilitatea apariției unor reacții adverse la anumite produse ce intra in alcătuirea dispozitivelor medicale.</b>
        <br>
        <br>
        <br>

        Subsemnatul <b>{{ ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') }}</b> declar ca sunt de acord cu prelucrarea datelor cu caracter personal pentru realizarea obiectului prezentului contract.
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
        Beneficiarul este de acord pentru folosirea imaginii lui in scopul unei mai bune  informari a publicului asupra multiplelor posibilitati moderne de protezare/ortezare
        <br>
        <br>

        Prezentul act se incheie in 2 exemplare, cate unul de fiecare parte.

        <div style="page-break-after: always"></div>
        <br>
        <br>
        <b>*) Anexele la contract:</b>
        <br><br>
        nota de informare si acord prelucrare date personale - anexa nr. 1
        <br>
        ofertă de preţ - anexa nr. 2
        <br>
        fişă individuală de măsurători - anexa nr. 3
        <br>
        proces-verbal de predare-primire - anexa nr. 4
        <br>
        certificat de garanţie - anexa nr. 5
        <br>
        <br>
        <br>
        Data: ......................................................
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Semnătura ......................................................
        <br>
        <br>
        Data: ......................................................
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Semnătura ......................................................

        <br><br><br><br><br><br><br><br><br><br>

        <table style="text-align: center">
            <tr>
                <td width="50%" style="text-align: center">
                    Furnizor servicii de protezare/ortezare
                    <br>
                </td>
                <td width="50%" style="text-align: center">
                    Beneficiar
                </td>
            </tr>
            <tr>
                <td style="text-align: center">SC THERANOVA PROTEZARE SRL</td>
                <td style="text-align: center">{{ ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') }}</td>
            </tr>
        </table>

        <br><br><br>

        Data: ......................................................
        <br><br>
        Localitatea: ......................................................
        <br><br><br><br><br><br><br><br><br><br>
    </main>

    <div style="page-break-after: always"></div>

    <main style="">
        <b>ANEXA NR. 1</b>
        <br>
        <p style="text-align: center;">
            <b>NOTA DE INFORMARE SI ACORD PRELUCRARE DATE PERSONALE</b>
        </p>
        <p>SC THERANOVA PROTEZARE SRL  cu sediul în Oradea, Str. Eroul Necunoscut Nr. 2 România prelucreaza date cu caracter personal ale persoanelor fizice conform prevederilor legale respectând prevederile Regulamentului (UE) 2016/679 al Parlamentului European şi al Consiliului din 27 aprilie 2016 privind protecţia persoanelor fizice în ceea ce priveşte prelucrarea datelor cu caracter personal şi privind libera circulaţie a acestor date şi de abrogare a Directivei 95/46/CE (Regulamentul general privind protecţia datelor) pus în aplicare prin Legea nr.190/2018 . (Regulamentul general privind protectia datelor – GDPR).</p>
        <p><b>Datele cu caracter personal prelucrate de catre</b> SC THERANOVA PROTEZARE SRL:</p>
        <ul>
            <li>Informatii generale: nume, prenume, adresa de domiciliu, e-mail, telefon, data nasterii, CNP, ID de pacient, loc de munca, certificat de nastere, Copie CI, semnatura, nationalitate, sex, date mama/tata;</li>
            <li>Date de sanatate sau conexe activitatii SC THERANOVA PROTEZARE SRL  in legatura cu clienti-pacienti;</li>
        </ul>
        <p><b>Drepturile persoanelor vizate</b></p>
        <p>SC THERANOVA PROTEZARE SRL respecta drepturile pe care Legea nr. 190/2018 si Regulamentul UE 2016/679 le confera persoanei fizice vizate, respectiv:</p>
        <p>(i) dreptul de informare: dreptul de a fi informat cu privire la identitatea operatorului, scopul in care se face prelucrarea datelor, destinatarii sau categoriile de destinatari ai datelor, existenta drepturilor prevazute de lege pentru persoana vizata si conditiile in care pot fi exercitate;</p>
        <p>(ii) dreptul de acces la datele cu caracter personal: dreptul de a obtine la cerere si in mod gratuit, o data pe an, confirmarea faptului ca datele cu caracter personal sunt sau nu prelucrate de catre SC THERANOVA PROTEZARE SRL  </p>
        <p>(iii) dreptul de a nu fi supus unei decizii individuale: dreptul de a cere si de a obtine retragerea, anularea sau reevaluarea oricarei decizii care produce efecte juridice in privinta persoanei vizate, adoptata exclusiv pe baza unei prelucrari de date cu caracter personal, efectuata prin mijloace automate, destinata sa evalueze unele aspecte ale evolutiei, comportamentului si performantelor sau orice asemenea caracteristici obtinute de client/pacient in perioada ulterioara montarii/achizitionarii dispozitivelor medicale ori alte asemenea aspecte.</p>
        <p>(iv) dreptul de a se adresa justitiei: dreptul de a se adresa Autoritatii Nationale de Supraveghere a Prelucrarii Datelor cu Caracter Personal sau justitiei, pentru apararea oricaror drepturi garantate de prevederile legale care au fost incalcate;</p>
        <p>(v) dreptul la portabilitatea datelor: Persoana vizata are dreptul de a primi datele cu caracter personal care o privesc si pe care le-a furnizat operatorului intr-un format structurat, utilizat in mod curent si care poate fi citit automat si are dreptul de a transmite aceste date altui operator, fara obstacole din partea operatorului caruia i-au fost furnizate datele cu caracter personal;</p>
        <p>(vi) dreptul la restrictionarea datelor: Persoana vizata are dreptul de a obtine din partea operatorului restrictionarea prelucrarii in cazul in care se aplica unul din urmatoarele cazuri: (a)  persoana vizata contesta exactitatea datelor, pentru o perioada care ii permite operatorului sa verifice exactitatea datelor; (b)  prelucrarea este ilegala, iar persoana vizata se opune stergerii datelor cu caracter personal, solicitand in schimb restrictionarea utilizarii lor; (c)  operatorul nu mai are nevoie de datele cu caracter personal in scopul prelucrarii, dar persoana vizata i le solicita pentru constatarea, exercitarea sau apararea unui drept in instanta; sau (d)  persoana vizata s-a opus prelucrarii pentru intervalul de timp in care se verifica daca drepturile legitime ale operatorului prevaleaza asupra celor ale persoanei vizate.</p>
        <p>(vii) dreptul la opozitie: In orice moment, persoana vizata are dreptul de a se opune, din motive legate de situatia particulara in care se afla, a datelor cu caracter personal care o privesc, inclusiv crearii de profiluri pe baza . Operatorul nu mai prelucreaza datele cu caracter personal, cu exceptia cazului in care operatorul demonstreaza ca are motive legitime si imperioase care justifica prelucrarea si care prevaleaza asupra intereselor, drepturilor si libertatilor persoanei vizate sau ca scopul este constatarea, exercitarea sau apararea unui drept in instanta.</p>
        <p>(vi) dreptul la stergerea datelor: Persoana vizata are dreptul de a obtine din partea operatorului stergerea datelor cu caracter personal care o privesc, fara intarzieri nejustificate, iar operatorul are obligatia de a sterge datele cu caracter personal fara intarzieri nejustificate in cazul in care se aplica unul dintre urmatoarele motive: (a)  datele cu caracter personal nu mai sunt necesare pentru indeplinirea scopurilor pentru care au fost colectate sau prelucrate; (b)  persoana vizata isi retrage consimtamantul pe baza caruia are loc prelucrarea si nu exista niciun alt temei juridic pentru prelucrarea; (c)  persoana vizata se opune prelucrarii nu exista motive legitime care sa prevaleze in ceea ce priveste prelucrarea sau persoana vizata se opune prelucrarii); (d)  datele cu caracter personal au fost prelucrate ilegal; (e)  datele cu caracter personal trebuie sterse pentru respectarea unei obligatii legale care revine operatorului in temeiul dreptului Uniunii sau al dreptului intern sub incidenta caruia se afla operatorul; </p>
        <p>(vix) dreptul la rectificare: Persoana vizata are dreptul de a obtine de la operator, fara intarzieri nejustificate, rectificarea datelor cu caracter personal inexacte care o privesc. Tinandu-se seama de scopurile in care au fost prelucrate datele, persoana vizata are dreptul de a obtine completarea datelor cu caracter personal care sunt incomplete, inclusiv prin furnizarea unei declaratii suplimentare.</p>
        <p>Drepturile persoanelor vizate vor putea fi exercitate de catre persoana fizica adresand o cerere scrisa, datata si semnata catre SC THERANOVA PROTEZARE SRL , in care se vor mentiona datele personale (inclusiv un numar de telefon) si datele asupra carora se solicita accesul, interventia, motivul justificat si modul de acces, interventie sau datele asupra carora se solicita opozitia si motivul intemeiat si legitim legat de situatia particulara a persoanei. Oricarei cereri i se va atasa o copie xerox, lizibila a actului de identitate al solicitantului.</p>
        <br>
        <p><b>Prelucrarea datelor cu caracter personal</b></p>
        <p>Prelucrarea si stocarea datelor cu caracter personal este facuta in conditii de siguranta si in scopuri legitime legate in principal de desfasurarea activitatii de prestare a serviciilor de protezare si in subsidiar, pentru reclama, maketing, publicitate, precum si servicii de consultanta si cercetare.</p>
        <p>Prin completarea formularului de contract si a fisei de client va dati acordul in mod expres si neechivoc ca datele dumneavoastra cu caracter personal sa fie stocate si prelucrate de catre SC THERANOVA PROTEZARE SRL  . SC THERANOVA PROTEZARE SRL  va pastra confidentialitatea acestor informatii, cu exceptia informatiilor solicitate de autoritatile legale competente.</p>
        <br>
        <p>Scopul prelucrarii datelor cu caracter personal</p>
        <p>SC THERANOVA PROTEZARE SRL  va prelucra datele personale cu urmatoarele scopuri: (i) furnizarea de servicii medicale/de protezare; (ii) educatie si cultura; (iii) protectie si asistenta sociala, (iv) cercetare stiintifica, si (v) marketing si publicitate. Persoanele vizate sunt: (i) pacienti / potentiali pacienti ai SC THERANOVA PROTEZARE SRL  , (ii) debitori, (iii) membrii familiei clientilor-pacientilor, (iv), cadre tehnico-medicale (v) cadre didactice, (vi) studenti precum si (vii) persoanele de contact desemnate de catre pacienti/client.</p>
        <br>
        <p><b>Persoanele imputernicite/Destinatari/Operator asociat:</b></p>
        <p>Datele dumneavoastra cu caracter personal pot fi prelucrate de catre urmatoarele persoane, cu respectarea intocmai a legislatiei privind protectia datelor cu caracter personal:</p>
        <p>Datele personale prelucrate pot fi dezvaluite urmatorilor destinatari: (i) persoanei vizate (ii) reprezentantilor legali ai persoanei vizate (iii)  operatorilor de date (iv) partenerilor contractuali ai SC THERANOVA PROTEZARE SRL  – Furnizorii de prestari servicii cum ar fi, dar fara a se limita la furnizori de servicii si sisteme IT, partenerii contractuali precum si toate societatile din aceste categorii de destinatari de la care Societatea va contracta servicii si produse si care au luat masuri adecvate de protectie, conform prevederilor legale, pentru a asigura ca acestia isi respecta obligatiile privind protectia datelor cu caracter personal; (v) alte companii din acelasi grup cu SC THERANOVA PROTEZARE SRL  (vi) autoritatilor publice – precum Casa de Asigurari de Sanatate, DSP, autoritatea fiscala, etc. pe baza competentelor acestora prevazute de legea aplicabila, precum si oricare alte autoritati publice care pot solicita astfel de date in temeiul unor dispozitii legale (vii) institutii/centre de recuperare medicala si institutii de educatie; (vii) societatilor de asigurare si reasigurare; (viii) organizatii profesionale; (ix) asociatii si fundatii; (x) mass-media.</p>
        <br>
        <p><b>Transferul datelor cu caracter personal</b></p>
        <p>Datele personale pot fi dezvaluite unor terte parti, procesatoare de date personale, care se afla in strainatate, respectiv in oricare dintre tarile aflate in cadrul Uniunii Europene. De asemenea, anumite date personale pot fi dezvaluite in scopul raportarii actionarilor/conducerii SC THERANOVA PROTEZARE SRL . In cazul in care datele dumneavoastra se vor transfera catre alte societati din alte tari, in vederea initierii, incheierii si dezvoltarii unor contracte si/sau proiecte cu o asemenea entitate, veti fi informat si se vor aplica garantiile prevazute de art. 44-49 din Regulamentul General privind protectia datelor.</p>
        <p><b>Perioada de stocare a datelor cu caracter personal</b></p>
        <p>SC THERANOVA PROTEZARE SRL  asigura confidentialitatea datelor cu caracter personal prelucrate in conformitate cu acordul exprimat de persoana fizica vizata si conform prevederilor legale. Accesul la informatiile tratate drept confidentiale va fi limitat la acele persoane, care prin natura activitatii desfasurate, este necesar sa ia cunostinta de aceste informatii in scopul ducerii la indeplinire a scopului, raporturilor juridice nascute in relatie cu SC THERANOVA PROTEZARE SRL .Aceste persoane sunt tinute sa respecte caracterul confidential al acestor informatii, asumandu-si la randul lor obligatia de a asigura si pastra confidentialitatea acestor date si informatii si de a le prelucra in conformitate cu cerintele legale. Datele dumneavoastra care sunt necesare in scopuri legate de serviciile medicale/ de protezare vor fi stocate pe durata contractului de prestari servicii medicale/protezare, respectiv pe perioada de timp necesara in vederea indeplinirii obligatiilor legale prevazute de legislatia aplicabila .</p>
        <p>Datele legate de plati/facturare vor fi stocate pe o perioada de 10 ani, conform Legii nr. 82/1991 privind contabilitatea;</p>
        <ul>
            <li>Datele privind supravegherea video pentru asigurarea securitatii bunurilor si persoanelor, respectiv a inregistrarii apelurilor telefonice se vor stoca pe o perioada de 30 de zile calendaristice, respectiv in conformitate cu temeiurile prevazute de legislatia in vigoare;</li>
            <li>Datele inregistrarii apelurilor telefonice se vor stoca pe o perioada de 6 luni, respectiv in conformitate cu temeiurile prevazute de legislatia in vigoare, iar documentele incarcate pe site vor fi stocate pentru un termen de 30 de zile, ulterior acestui termen documentele vor fi sterse automat;</li>
            <li>Prelucrarea datelor in scop de marketing va avea loc pe durata relatiei contractuale cu SC THERANOVA PROTEZARE SRL , precum si dupa incetarea acesteia. In situatia in care persoana vizata isi retrage consimtamantul de marketing direct, datele sale nu vor mai fi prelucrate in acest scop, din momentul retragerii cconsimtamantului.</li>
            <li>De asemenea, datele persoanei vizate pot fi prelucrate si pe durata existentei unei obligatii legale pentru pastrarea datelor dumneavoastra, respectiv pe durata de existenta a unui alt temei justificativ legal, in conformitate cu exigentele art. 5 din Regulamentul General UE privind protectia datelor.</li>
        </ul>
        <p>Datele de contact ale responsabilului cu protectia datelor:____________</p>
        <p>
            Subsemnatul /a <b>{{ ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') }}</b>, avand urmatoarele date de identificare
            B.I./C.I. seria {{ substr(($fisaCaz->pacient->serie_numar_buletin ?? ''), 0, 2) }}, nr. {{ preg_replace('/[^0-9]/', '', ($fisaCaz->pacient->serie_numar_buletin)) }},
            eliberat/eliberată la data de {{ $fisaCaz->pacient->data_eliberare_buletin ? Carbon::parse($fisaCaz->pacient->data_eliberare_buletin)->isoFormat('DD.MM.YYYY') : '' }},
            <b>arat ca am citit si inteles pe deplin continutul informarii de mai sus si sunt de acord in totalitate cu prelucrarea datelor mele personale astfel cum rezulta din Nota de informare de mai sus.</b>
        </p>
        <table>
            <tr>
                <td style="width: 80%">Data:</td>
                <td>Nume, prenume</td>
            </tr>
        </table>
        <br><br>

        <p style="text-align: center;"><b>DECLARATIE DE CONSIMTAMANT</b></p>
        <br>
        <p>
            Subsemnatul /a <b>{{ ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') }}</b>, avand urmatoarele date de identificare
            B.I./C.I. seria {{ substr(($fisaCaz->pacient->serie_numar_buletin ?? ''), 0, 2) }}, nr. {{ preg_replace('/[^0-9]/', '', ($fisaCaz->pacient->serie_numar_buletin)) }},
            eliberat/eliberată la data de {{ $fisaCaz->pacient->data_eliberare_buletin ? Carbon::parse($fisaCaz->pacient->data_eliberare_buletin)->isoFormat('DD.MM.YYYY') : '' }},
            <b>imi exprim consimtamantul in mod expres pentru publicarea/postarea in mediul on-line in vederea promovarii serviciilor incluse in obiectul de activitate al THERANOVA PROTEZARE, cu scop de marketing, publicitate, promovare si arat ca sunt de acord cu publicarea fotografiilor si filmarilor in orice format continand imaginea subsemnatului pe pagini /platforme on-line (Facebook, Instagram, etc ) detinute /administrate de THERANOVA PROTEZARE. Prezentul consimtamant este valabil pana la revocarea expresa a acestuia prin Notificarea scrisa a THERANOVA PROTEZARE.</b>
        </p>
        <table>
            <tr>
                <td style="width: 80%">Data:</td>
                <td>Nume, prenume</td>
            </tr>
        </table>






        {{-- Here's the magic. This MUST be inside body tag. Page count / total, centered at bottom of page --}}
        <script type="text/php">
            if (isset($pdf)) {
                $text = "Pagina {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("helvetica");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width) / 2;
                $y = $pdf->get_height() - 30;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>


    </main>
</body>

</html>
