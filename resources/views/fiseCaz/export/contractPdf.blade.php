@php
    use Carbon\Carbon;

    $pacient = $fisaCaz->pacient;
    $oferta = $fisaCaz->ofertaAcceptata;
    $numePacient = trim(($pacient->nume ?? '') . ' ' . ($pacient->prenume ?? ''));
    $serieBuletin = substr((string) ($pacient->serie_numar_buletin ?? ''), 0, 2);
    $numarBuletin = preg_replace('/[^0-9]/', '', (string) ($pacient->serie_numar_buletin ?? ''));
@endphp

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Contract</title>
    <style>
        @page {
            margin: 0;
        }

        header {
            position: fixed;
            top: 20px;
            left: 0;
            right: 0;
            height: 250px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin-top: 4cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
        }

        * {
            text-indent: 0;
            text-align: justify;
            box-sizing: border-box;
        }

        p {
            margin: 0 0 6px 0;
        }

        ul {
            margin: 4px 0 6px 18px;
            padding: 0;
        }

        li {
            margin-bottom: 4px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            word-wrap: break-word;
        }

        th,
        td {
            padding: 4px 6px;
            border: 1px solid #000;
            vertical-align: top;
        }

        .no-border td,
        .no-border th {
            border: 0;
            padding: 2px 0;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .mb-8 {
            margin-bottom: 8px;
        }

        .mt-12 {
            margin-top: 12px;
        }

        .signature-line {
            display: inline-block;
            min-width: 210px;
            border-bottom: 1px solid #000;
            height: 14px;
            vertical-align: bottom;
        }

        .consent-box {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 1px solid #000;
            vertical-align: middle;
            margin: 0 6px;
        }

        .schedule-table th,
        .schedule-table td {
            text-align: center;
            min-height: 22px;
        }

        .schedule-table td.text-left {
            text-align: left;
        }
    </style>
</head>

<body>
    <header style="margin: 0; text-align: center;">
        <img src="{{ asset('images/logo2-400x103.jpg') }}" width="400" alt="Theranova">
    </header>

    <main style="background-image: url('{{ asset('images/contractBackground.jpg') }}'); background-size: 100%; background-repeat: no-repeat;">
        <p class="text-center" style="font-size: 150%;"><b>CONTRACT DE PRESTĂRI SERVICII</b></p>
        <p class="text-center" style="font-size: 120%;">
            Nr. {{ $oferta->contract_nr ?? '________' }}
            din data de {{ ($oferta->contract_data ?? null) ? Carbon::parse($oferta->contract_data)->isoFormat('DD.MM.YYYY') : '_________________' }}
        </p>

        <br>
        <br>

        <p><b>1. Părțile contractante:</b></p>
        <p><b>1.1. S.C. THERANOVA PROTEZARE S.R.L.</b>, denumit în continuare furnizor de servicii, cu sediul în Oradea, str. Eroul Necunoscut, nr. 2 județul Bihor, înregistrat la ORC Bihor sub nr. J05/1172/11.09.2003, codul de înregistrare fiscală RO 15736030, reprezentat prin Jaco du Plessis, în calitate de administrator.</p>
        <p><b>1.2. {{ $numePacient }}</b>, denumit în continuare beneficiar, domiciliat în localitatea {{ $pacient->localitate ?? '' }}, str. {{ $pacient->adresa ?? '' }}, județul/sectorul {{ $pacient->judet ?? '' }}, codul numeric personal {{ $pacient->cnp ?? '' }}, posesor al B.I./C.I. seria {{ $serieBuletin }}, nr. {{ $numarBuletin }}, eliberat/eliberată la data de {{ $pacient->data_eliberare_buletin ? Carbon::parse($pacient->data_eliberare_buletin)->isoFormat('DD.MM.YYYY') : '' }}.</p>

        <br>

        <p><b>2. Obiectul contractului</b> constă în realizarea unui act de protezare/ortezare sub forma confecționării <b>{{ $oferta->obiect_contract ?? '' }}</b>, pentru beneficiar, în condițiile și la prețul stabilit în prezentul act juridic.</p>

        <p><b>3. Durata contractului</b> se stabilește de comun acord cu beneficiarul.</p>
        <p><b>3.1.</b> Durata contractului poate fi prelungită prin act adițional, cu acordul părților.</p>

        <p><b>4. Prețul</b> stabilit pentru furnizarea serviciilor de protezare este de <b>{{ $oferta->pret ?? '' }} lei</b>.</p>
        <p>S.C. THERANOVA PROTEZARE S.R.L. nu își asumă răspunderea pentru modificarea prețurilor practicate de către producător.</p>

        <br>

        <p><b>5. Etapele furnizării serviciilor de protezare</b></p>
        <p><b>5.1. Redactarea ofertei de preț</b></p>
        <p>Oferta de preț este un material informativ redactat de <b>S.C. THERANOVA PROTEZARE S.R.L.</b>, în calitate de furnizor de servicii de protezare, care conține detalii referitoare la producătorul, calitatea, cantitatea și prețul viitoarei proteze/orteze.</p>
        <p>Documentul este valabil 10 zile lucrătoare și nu generează drepturi sau obligații în favoarea/sarcina nici uneia dintre părți. Oferta de preț reprezintă parte integrantă a prezentului contract - <b>anexa nr. 2</b>.</p>

        <p><b>5.2. Transmiterea notei de comandă</b></p>
        <p>Nota de comandă este un document semnat și transmis furnizorului de servicii de protezare/ortezare de către beneficiar, în concordanță cu informațiile din cuprinsul ofertei de preț. Transmiterea notei de comandă reprezintă acordul de voință al beneficiarului cu privire la contractarea serviciilor furnizorului de proteze/orteze. Nota de comandă reprezintă parte integrantă a ofertei de preț - <b>anexa nr. 2</b>.</p>

        <p><b>5.3. Completarea fișei individuale de măsurători</b></p>
        <p>În baza notei de comandă transmise de către beneficiar se efectuează măsurători în vederea completării datelor tehnice necesare confecționării protezei provizorii sau definitive. Fișa individuală de măsurători este parte integrantă a prezentului contract - <b>anexa nr. 3</b>.</p>

        <p><b>5.4. Condiții de plată</b></p>
        <p>Indiferent de varianta de achiziție pentru care optează beneficiarul - plata integrală sau prin Casa de Asigurări de Sănătate beneficiarul va achita furnizorului contravaloarea dispozitivului medical după cum urmează:</p>
        <ul>
            <li>la deschiderea comenzii va achita un avans de 70% din valoarea dispozitivelor medicale (cei care dețin o decizie eliberată de cate CAS a cărei valoare nu acoperă integral cuantumul avansului vor trebui sa achite diferența),</li>
            <li>20% la predarea primei proteze/orteze intermediare,</li>
            <li>10% la livrarea protezei/ortezei finale.</li>
        </ul>
        <p>Plata se va face in contul SC Theranova Protezare SRL deschis la:</p>
        <p><b>Banca Transilvania Sucursala Oradea, cont IBAN RON: RO71BTRL00501202765924XX</b></p>

        <p><b>5.5. Predarea/primirea dispozitivelor medicale</b></p>
        <p><b><i>Predarea protezei finale se va face pentru pacienții aflați la prima protezare, în termen de maximum 5 luni de la predarea protezei provizorii, iar pentru pacienții purtători de proteze în termen de maximum 3 luni.</i></b> Data predării/primirii protezei finale se va stabili de comun acord cu beneficiarul în intervalul menționat anterior la sediul furnizorului de servicii de protezare în intervalul orar de funcționare a instituției. Procesul verbal de predare-primire este însoțit de certificatul de garanție al dispozitivului medical.</p>
        <p>În cazul nerespectării datei stabilite de comun acord se va fixa o nouă dată în acest sens, dată care nu poate să depășească 7 zile calendaristice de la data programării inițiale. Dacă beneficiarul refuză sau nu se prezintă la data stabilită, furnizorului de servicii de protezare/ortezare nu-i va putea imputa nepredarea la termen a dispozitivelor medicale. Pentru pacienții care amână nejustificat predarea protezei finale, după cea de a doua programare neonorată, centrul de protezare/ortezare va considera procesul de protezare încheiat la stadiul actual al protezei, urmând ca pentru definitivarea protezei să se emită o nouă ofertă.</p>
        <p>În cazul în care beneficiarul nu se prezintă la programare, renunță la comandă sau refuză ridicarea dispozitivului medical, avansul achitat nu se restituie de către furnizor, suma încasată reprezentând despăgubiri pentru daunele provocate prin denunțarea contractului.</p>
        <p>Pacientul declară că a luat la cunoștință că, în situația în care decizia CAS nu este obținută în termenul menționat anterior, contravaloarea etapelor tehnice de protezare deja efectuate nu se restituie și nu se scade din contribuția de asigurat.</p>

        <p class="mb-8">Semnătura: <span class="signature-line"></span></p>

        <table class="schedule-table">
            <thead>
                <tr>
                    <th style="width: 10%;">Nr. crt.</th>
                    <th style="width: 28%;">Etapa predare</th>
                    <th style="width: 22%;">Data programare</th>
                    <th style="width: 20%;">Data predare</th>
                    <th style="width: 20%;">Semnătura pacient</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1.</td>
                    <td class="text-left">Proteza provizorie</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td class="text-left">Proteza definitivă</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>

        <p><b>Termenul de garanție privind execuția este de 14 zile pentru proteza provizorie - cupa diagnostic, respectiv de 24 luni pentru proteza definitivă.</b> Garanția de conformitate a produselor este de 24 luni. Termenul de garanție este de 24 luni pentru componentele structurale și manoperă.</p>
        <p><b>Garanția se referă la manopera pentru executarea cupei protetice, iar pentru restul componentelor dispozitivului medical (labă protetică, tub, adaptoare, liner silicon, articulație genunchi etc.) garanția și termenul de garanție al respectivelor produse este asigurată de către producător și intră în vigoare din momentul în care sunt puse în folosință, adică predarea protezei provizorii cu componentele protetice achiziționate.</b> Garanția pentru întreg dispozitivul medical nu este valabilă în cazul în care defecțiunea apare din culpa beneficiarului prin nerespectarea instrucțiunilor de manipulare, precum și în cazurile în care reparațiile se impun din pricina schimbării condițiilor anatomice și/sau fiziologice ale beneficiarului (exemplu modificări de volum ale bontului). Procesul verbal de predare primire - anexa 4 - și certificatul de garanție - <b>anexa 5</b> - sunt părți integrante ale prezentului contract.</p>

        <br>

        <p><b>6. Drepturile părților</b></p>
        <p><b>6.1. Furnizorul de servicii de protezare/ortezare are următoarele drepturi:</b></p>
        <p>a) Să beneficieze la termenele stabilite de plata avansului/prețului dispozitivelor medicale furnizate beneficiarului în conformitate cu nota de comandă transmisă de acesta și a fișei individuale de măsurători;</p>
        <p>b) Să beneficieze de returnarea protezei/ortezei provizorii în momentul finalizării și predării către beneficiar a protezei definitive (nereturnarea protezei provizorii implică cheltuieli suplimentare care nu sunt cuprinse în preț - peste valoarea ofertei);</p>
        <p>c) Să beneficieze de acoperirea eventualelor prejudicii rezultate din culpa beneficiarului conform reglementărilor de la <b>pct. 8</b> ale prezentului contract.</p>

        <p><b>6.2. Beneficiarul are următoarele drepturi:</b></p>
        <p>a) Să beneficieze de dispozitive medicale conform măsurătorilor consemnate în fișa individuală de măsurători semnată de acesta;</p>
        <p>b) Să primească dispozitivele medicale la data stabilită de comun accord cu furnizorul de dispozitive medicale;</p>
        <p>c) Să beneficieze de instrucțiuni corespunzătoare în vederea utilizării în condiții optime a dispozitivelor medicale;</p>
        <p>d) Să beneficieze cu titlu gratuit de service în perioada de garanție a dispozitivelor medicale și contra cost, ulterior expirării acestui termen.</p>

        <br>

        <p><b>7. Obligațiile părților</b></p>
        <p><b>7.1. Furnizorul de servicii de protezare/ortezare are următoarele obligații:</b></p>
        <p>a) Să confecționeze dispozitivele medicale conform măsurătorilor consemnate în fișa de măsurători individuale semnată de beneficiar, fără a fi răspunzător de schimbările anatomice și/sau fiziologice ale bontului apărute de la luarea mulajului până la predarea protezei;</p>
        <p>b) Să predea dispozitivele medicale la data stabilită de comun acord cu beneficiarul dispozitivului medical prin proces-verbal semnat de ambele părți;</p>
        <p>c) Să acorde instrucțiunile necesare pentru folosirea optimă a dispozitivelor medicale;</p>
        <p>d) Să asigure service cu titlu gratuit în perioada de garanție la sediul societății, conform programului zilnic de lucru;</p>
        <p>e) Să asigure service contra cost după expirarea termenului de garanție;</p>
        <p>f) Tehnicianul se angajează să recomande pacientului varianta optimă de protezare, refuzul pacientului de a urma indicațiile consemnat prin proces-verbal absolvă de orice răspundere Centrul de Protezare și Ortezare Theranova.</p>

        <p><b>7.2. Beneficiarul are următoarele obligații:</b></p>
        <p>a) Să se prezinte la furnizorul de servicii în vederea protezării definitive/provizorii în termenele stabilite de comun accord;</p>
        <p>b) Să depună toată diligența în vederea obținerii deciziei care atestă cofinanțarea dispozitivului medical prin sistemul de asigurări sociale de sănătate, în cazul în care beneficiarul agreează această variantă de cofinanțare;</p>
        <p>c) Să fie prezent la data stabilită de comun acord pentru predarea dispozitivului medical; în caz contrar furnizorul de servicii va proceda la transmiterea dispozitivului medical sub formă de colet poștal;</p>
        <p>d) Să folosească și să întrețină dispozitivele medicale conform instrucțiunilor furnizorului de servicii;</p>
        <p>e) Să plătească în conformitate cu cele stipulate la pct. 5.4;</p>
        <p>f) Să predea furnizorului de servicii proteza provizorie în momentul obținerii protezei definitive;</p>
        <p>g) Să prezinte certificatul de garanție în vederea înregistrării tuturor intervențiilor care au loc în perioada de garanție;</p>
        <p>h) Să informeze Centrul de Protezare și Ortezare Theranova în cel mai scurt timp de la data apariției, asupra problemelor pe care le identifică pe durata utilizării dispozitivului medical furnizat;</p>
        <p>i) Să acopere eventualele prejudicii cauzate furnizorului de servicii din culpa sa conform reglementărilor de la pct. 8 ale prezentului contract;</p>
        <p>j) În cazul în care pacientul dorește o anumită soluție de protezare, acesta își va asuma răspunderea pentru eventualele prejudicii sau inconveniente ulterioare;</p>
        <p>k) În cazul în care primește spre folosință, în perioada de probă sau pe perioada asigurării service-ului, anumite componente protetice (picior protetic, articulație genunchi, mână protetică i-Limb, mână protetică BeBionic etc.), acesta este obligat să le restituie, la finele perioadei respective, în aceeași stare de funcționare ca și cea inițială, orice pagube rezultate urmare a nerestituirii, utilizării necorespunzătoare a acestora sau din orice alte motive imputabile acestuia, vor fi suportate integral de către acesta.</p>

        <br>

        <p><b>8. Riscuri contractuale</b></p>
        <p>Beneficiarul răspunde pentru cheltuielile contractate de furnizorul de servicii în vederea confecționării dispozitivelor medicale în cazul în care solicită rezilierea contractului. În astfel de situații, beneficiarul va fi obligat la plata integrală a sumelor investite de către furnizorul de servicii în vederea acoperirii tuturor cheltuielilor și a prejudiciului acestuia.</p>

        <br>

        <p><b>9. Încetarea contractului</b></p>
        <p>Constituie motiv de încetare a prezentului contract următoarele:</p>
        <p>a) Expirarea duratei pentru care a fost încheiat contractul;</p>
        <p>b) Acordul părților privind încetarea contractului;</p>
        <p>c) Scopul contractului a fost atins;</p>
        <p>d) Forța majoră, dacă este invocată;</p>
        <p>e) În caz de deces/desființare a uneia din părțile contractante.</p>

        <br>

        <p><b>10. Dispoziții finale</b></p>
        <p>Părțile contractante au dreptul, pe durata îndeplinirii prezentului contract, de a conveni modificarea clauzelor acestuia prin act adițional numai în cazul apariției unor circumstanțe care lezează interesele legitime ale acestora și care nu au putut fi prevăzute la data încheierii prezentului contract. Prevederile prezentului contract se vor completa cu prevederile legislației în vigoare în domeniu. Limba care guvernează prezentul contract este limba română. Prezentul contract va fi interpretat conform legilor din România. Eventualele litigii se vor soluționa pe cale amiabilă, iar în caz de neînțelegeri de către instanțele judecătorești competente de la sediul furnizorului de servicii.</p>

        <br>

        <p><b>Toate componentele folosite de S.C. THERANOVA PROTEZARE S.R.L. la confecționarea dispozitivelor medicale sunt certificate și sunt în concordanță cu normele și standardele UE.</b></p>
        <p><b>Beneficiarul a fost informat referitor la posibilitatea apariției unor reacții adverse la anumite produse ce intră în alcătuirea dispozitivelor medicale.</b></p>

        <br>
        <br>

        <p>Subsemnatul <b>{{ $numePacient }}</b> declar că sunt de acord cu prelucrarea datelor cu caracter personal pentru realizarea obiectului prezentului contract.</p>
        <p><span class="consent-box"></span> Beneficiarul este de acord pentru folosirea imaginii lui în scopul unei mai bune informări a publicului asupra multiplelor posibilități moderne de protezare/ortezare.</p>

        <br>

        <p>Prezentul act se încheie în 2 exemplare, câte unul de fiecare parte.</p>

        <div style="page-break-after: always;"></div>

        <p><b>*) Anexele la contract:</b></p>
        <br>
        <p>nota de informare și acord prelucrare date personale - anexa nr. 1</p>
        <p>ofertă de preț - anexa nr. 2</p>
        <p>fișă individuală de măsurători - anexa nr. 3</p>
        <p>proces-verbal de predare-primire - anexa nr. 4</p>
        <p>certificat de garanție - anexa nr. 5</p>

        <br>
        <br>
        <br>

        <table class="no-border">
            <tr>
                <td style="width: 50%;" class="text-left">Data: ......................................................</td>
                <td style="width: 50%;" class="text-right">Semnătura ......................................................</td>
            </tr>
            <tr>
                <td style="width: 50%;" class="text-left">Data: ......................................................</td>
                <td style="width: 50%;" class="text-right">Semnătura ......................................................</td>
            </tr>
        </table>

        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>

        <table class="no-border">
            <tr>
                <td width="50%" class="text-center">Furnizor servicii de protezare/ortezare</td>
                <td width="50%" class="text-center">Beneficiar</td>
            </tr>
            <tr>
                <td class="text-center">SC THERANOVA PROTEZARE SRL</td>
                <td class="text-center">{{ $numePacient }}</td>
            </tr>
        </table>

        <br>
        <br>
        <br>

        <p>Data: ......................................................</p>
        <br>
        <p>Localitatea: ......................................................</p>

        <div style="page-break-after: always;"></div>
    </main>

    <main>

        <p><b>ANEXA NR. 1</b></p>
        <p class="text-center"><b>NOTA DE INFORMARE SI ACORD PRELUCRARE DATE PERSONALE</b></p>
        <p>SC THERANOVA PROTEZARE SRL cu sediul în Oradea, Str. Eroul Necunoscut Nr. 2 România prelucrează date cu caracter personal ale persoanelor fizice conform prevederilor legale respectând prevederile Regulamentului (UE) 2016/679 al Parlamentului European și al Consiliului din 27 aprilie 2016 privind protecția persoanelor fizice în ceea ce privește prelucrarea datelor cu caracter personal și privind libera circulație a acestor date și de abrogare a Directivei 95/46/CE (Regulamentul general privind protecția datelor) pus în aplicare prin Legea nr. 190/2018 (Regulamentul general privind protecția datelor - GDPR).</p>

        <br>

        <p><b>Datele cu caracter personal prelucrate de către</b> SC THERANOVA PROTEZARE SRL:</p>
        <ul>
            <li>Informații generale: nume, prenume, adresă de domiciliu, e-mail, telefon, data nașterii, CNP, ID de pacient, loc de muncă, certificat de naștere, copie CI, semnătură, naționalitate, sex, date mamă/tată;</li>
            <li>Date de sănătate sau conexe activității SC THERANOVA PROTEZARE SRL în legătură cu clienți-pacienți;</li>
        </ul>

        <p><b>Drepturile persoanelor vizate</b></p>
        <p>SC THERANOVA PROTEZARE SRL respectă drepturile pe care Legea nr. 190/2018 și Regulamentul UE 2016/679 le conferă persoanei fizice vizate, respectiv:</p>
        <p>(i) dreptul de informare: dreptul de a fi informat cu privire la identitatea operatorului, scopul în care se face prelucrarea datelor, destinatarii sau categoriile de destinatari ai datelor, existența drepturilor prevăzute de lege pentru persoana vizată și condițiile în care pot fi exercitate;</p>
        <p>(ii) dreptul de acces la datele cu caracter personal: dreptul de a obține la cerere și în mod gratuit, o dată pe an, confirmarea faptului că datele cu caracter personal sunt sau nu prelucrate de către SC THERANOVA PROTEZARE SRL;</p>
        <p>(iii) dreptul de a nu fi supus unei decizii individuale: dreptul de a cere și de a obține retragerea, anularea sau reevaluarea oricărei decizii care produce efecte juridice în privința persoanei vizate, adoptată exclusiv pe baza unei prelucrări de date cu caracter personal, efectuată prin mijloace automate, destinată să evalueze unele aspecte ale evoluției, comportamentului și performanțelor sau orice asemenea caracteristici obținute de client/pacient în perioada ulterioară montării/achiziționării dispozitivelor medicale ori alte asemenea aspecte;</p>
        <p>(iv) dreptul de a se adresa justiției: dreptul de a se adresa Autorității Naționale de Supraveghere a Prelucrării Datelor cu Caracter Personal sau justiției, pentru apărarea oricăror drepturi garantate de prevederile legale care au fost încălcate;</p>
        <p>(v) dreptul la portabilitatea datelor: persoana vizată are dreptul de a primi datele cu caracter personal care o privesc și pe care le-a furnizat operatorului într-un format structurat, utilizat în mod curent și care poate fi citit automat și are dreptul de a transmite aceste date altui operator, fără obstacole din partea operatorului căruia i-au fost furnizate datele cu caracter personal;</p>
        <p>(vi) dreptul la restricționarea datelor: persoana vizată are dreptul de a obține din partea operatorului restricționarea prelucrării în cazul în care se aplică unul din următoarele cazuri: (a) persoana vizată contestă exactitatea datelor, pentru o perioadă care îi permite operatorului să verifice exactitatea datelor; (b) prelucrarea este ilegală, iar persoana vizată se opune ștergerii datelor cu caracter personal, solicitând în schimb restricționarea utilizării lor; (c) operatorul nu mai are nevoie de datele cu caracter personal în scopul prelucrării, dar persoana vizată i le solicită pentru constatarea, exercitarea sau apărarea unui drept în instanță; sau (d) persoana vizată s-a opus prelucrării pentru intervalul de timp în care se verifică dacă drepturile legitime ale operatorului prevalează asupra celor ale persoanei vizate;</p>
        <p>(vii) dreptul la opoziție: în orice moment, persoana vizată are dreptul de a se opune, din motive legate de situația particulară în care se află, prelucrării datelor cu caracter personal care o privesc, inclusiv creării de profiluri pe baza acestora. Operatorul nu mai prelucrează datele cu caracter personal, cu excepția cazului în care operatorul demonstrează că are motive legitime și imperioase care justifică prelucrarea și care prevalează asupra intereselor, drepturilor și libertăților persoanei vizate sau că scopul este constatarea, exercitarea sau apărarea unui drept în instanță;</p>
        <p>(viii) dreptul la ștergerea datelor: persoana vizată are dreptul de a obține din partea operatorului ștergerea datelor cu caracter personal care o privesc, fără întârzieri nejustificate, iar operatorul are obligația de a șterge datele cu caracter personal fără întârzieri nejustificate în cazul în care se aplică unul dintre următoarele motive: (a) datele cu caracter personal nu mai sunt necesare pentru îndeplinirea scopurilor pentru care au fost colectate sau prelucrate; (b) persoana vizată își retrage consimțământul pe baza căruia are loc prelucrarea și nu există niciun alt temei juridic pentru prelucrare; (c) persoana vizată se opune prelucrării și nu există motive legitime care să prevaleze în ceea ce privește prelucrarea; (d) datele cu caracter personal au fost prelucrate ilegal; (e) datele cu caracter personal trebuie șterse pentru respectarea unei obligații legale care revine operatorului în temeiul dreptului Uniunii sau al dreptului intern sub incidența căruia se află operatorul;</p>
        <p>(ix) dreptul la rectificare: persoana vizată are dreptul de a obține de la operator, fără întârzieri nejustificate, rectificarea datelor cu caracter personal inexacte care o privesc. Ținându-se seama de scopurile în care au fost prelucrate datele, persoana vizată are dreptul de a obține completarea datelor cu caracter personal care sunt incomplete, inclusiv prin furnizarea unei declarații suplimentare.</p>
        <p>Drepturile persoanelor vizate vor putea fi exercitate de către persoana fizică adresând o cerere scrisă, datată și semnată către SC THERANOVA PROTEZARE SRL, în care se vor menționa datele personale (inclusiv un număr de telefon) și datele asupra cărora se solicită accesul, intervenția, motivul justificat și modul de acces, intervenție sau datele asupra cărora se solicită opoziția și motivul întemeiat și legitim legat de situația particulară a persoanei. Oricărei cereri i se va atașa o copie xerox, lizibilă, a actului de identitate al solicitantului.</p>

        <br>

        <p><b>Prelucrarea datelor cu caracter personal</b></p>
        <p>Prelucrarea și stocarea datelor cu caracter personal este făcută în condiții de siguranță și în scopuri legitime legate în principal de desfășurarea activității de prestare a serviciilor de protezare și, în subsidiar, pentru reclamă, marketing, publicitate, precum și servicii de consultanță și cercetare.</p>
        <p>Prin completarea formularului de contract și a fișei de client vă dați acordul în mod expres și neechivoc ca datele dumneavoastră cu caracter personal să fie stocate și prelucrate de către SC THERANOVA PROTEZARE SRL. SC THERANOVA PROTEZARE SRL va păstra confidențialitatea acestor informații, cu excepția informațiilor solicitate de autoritățile legale competente.</p>

        <br>

        <p><b>Scopul prelucrării datelor cu caracter personal</b></p>
        <p>SC THERANOVA PROTEZARE SRL va prelucra datele personale cu următoarele scopuri: (i) furnizarea de servicii medicale/de protezare; (ii) educație și cultură; (iii) protecție și asistență socială; (iv) cercetare științifică; și (v) marketing și publicitate. Persoanele vizate sunt: (i) pacienți/potențiali pacienți ai SC THERANOVA PROTEZARE SRL; (ii) debitori; (iii) membrii familiei clienților-pacienți; (iv) cadre tehnico-medicale; (v) cadre didactice; (vi) studenți, precum și (vii) persoanele de contact desemnate de către pacient/client.</p>

        <br>

        <p><b>Persoanele împuternicite/Destinatari/Operator asociat:</b></p>
        <p>Datele dumneavoastră cu caracter personal pot fi prelucrate de către următoarele persoane, cu respectarea întocmai a legislației privind protecția datelor cu caracter personal:</p>
        <p>Datele personale prelucrate pot fi dezvăluite următorilor destinatari: (i) persoanei vizate; (ii) reprezentanților legali ai persoanei vizate; (iii) operatorilor de date; (iv) partenerilor contractuali ai SC THERANOVA PROTEZARE SRL - furnizorii de prestări servicii cum ar fi, dar fără a se limita la furnizori de servicii și sisteme IT, partenerii contractuali precum și toate societățile din aceste categorii de destinatari de la care Societatea va contracta servicii și produse și care au luat măsuri adecvate de protecție, conform prevederilor legale, pentru a asigura că aceștia își respectă obligațiile privind protecția datelor cu caracter personal; (v) alte companii din același grup cu SC THERANOVA PROTEZARE SRL; (vi) autorităților publice - precum Casa de Asigurări de Sănătate, DSP, autoritatea fiscală etc., pe baza competențelor acestora prevăzute de legea aplicabilă, precum și oricăror alte autorități publice care pot solicita astfel de date în temeiul unor dispoziții legale; (vii) instituții/centre de recuperare medicală și instituții de educație; (viii) societăților de asigurare și reasigurare; (ix) organizații profesionale; (x) asociații și fundații; (xi) mass-media.</p>

        <br>

        <p><b>Transferul datelor cu caracter personal</b></p>
        <p>Datele personale pot fi dezvăluite unor terțe părți, procesatoare de date personale, care se află în străinătate, respectiv în oricare dintre țările aflate în cadrul Uniunii Europene. De asemenea, anumite date personale pot fi dezvăluite în scopul raportării acționarilor/conducerii SC THERANOVA PROTEZARE SRL. În cazul în care datele dumneavoastră se vor transfera către alte societăți din alte țări, în vederea inițierii, încheierii și dezvoltării unor contracte și/sau proiecte cu o asemenea entitate, veți fi informat și se vor aplica garanțiile prevăzute de art. 44-49 din Regulamentul General privind protecția datelor.</p>

        <p><b>Perioada de stocare a datelor cu caracter personal</b></p>
        <p>SC THERANOVA PROTEZARE SRL asigură confidențialitatea datelor cu caracter personal prelucrate în conformitate cu acordul exprimat de persoana fizică vizată și conform prevederilor legale. Accesul la informațiile tratate drept confidențiale va fi limitat la acele persoane, care prin natura activității desfășurate este necesar să ia cunoștință de aceste informații în scopul ducerii la îndeplinire a scopului, raporturilor juridice născute în relație cu SC THERANOVA PROTEZARE SRL. Aceste persoane sunt ținute să respecte caracterul confidențial al acestor informații, asumându-și la rândul lor obligația de a asigura și păstra confidențialitatea acestor date și informații și de a le prelucra în conformitate cu cerințele legale. Datele dumneavoastră care sunt necesare în scopuri legate de serviciile medicale/de protezare vor fi stocate pe durata contractului de prestări servicii medicale/protezare, respectiv pe perioada de timp necesară în vederea îndeplinirii obligațiilor legale prevăzute de legislația aplicabilă.</p>
        <p>Datele legate de plăți/facturare vor fi stocate pe o perioadă de 10 ani, conform Legii nr. 82/1991 privind contabilitatea;</p>
        <ul>
            <li>Datele privind supravegherea video pentru asigurarea securității bunurilor și persoanelor, respectiv a înregistrării apelurilor telefonice, se vor stoca pe o perioadă de 30 de zile calendaristice, respectiv în conformitate cu temeiurile prevăzute de legislația în vigoare;</li>
            <li>Datele înregistrării apelurilor telefonice se vor stoca pe o perioadă de 6 luni, respectiv în conformitate cu temeiurile prevăzute de legislația în vigoare, iar documentele încărcate pe site vor fi stocate pentru un termen de 30 de zile; ulterior acestui termen documentele vor fi șterse automat;</li>
            <li>Prelucrarea datelor în scop de marketing va avea loc pe durata relației contractuale cu SC THERANOVA PROTEZARE SRL, precum și după încetarea acesteia. În situația în care persoana vizată își retrage consimțământul de marketing direct, datele sale nu vor mai fi prelucrate în acest scop, din momentul retragerii consimțământului;</li>
            <li>De asemenea, datele persoanei vizate pot fi prelucrate și pe durata existenței unei obligații legale pentru păstrarea datelor dumneavoastră, respectiv pe durata de existență a unui alt temei justificativ legal, în conformitate cu exigențele art. 5 din Regulamentul General UE privind protecția datelor.</li>
        </ul>
        <p>Datele de contact ale responsabilului cu protecția datelor: ____________</p>

        <p>Subsemnatul/a <b>{{ $numePacient }}</b>, având următoarele date de identificare B.I./C.I. seria {{ $serieBuletin }}, nr. {{ $numarBuletin }}, eliberat/eliberată la data de {{ $pacient->data_eliberare_buletin ? Carbon::parse($pacient->data_eliberare_buletin)->isoFormat('DD.MM.YYYY') : '' }}, <b>arat că am citit și înțeles pe deplin conținutul informării de mai sus și sunt de acord în totalitate cu prelucrarea datelor mele personale astfel cum rezultă din Nota de informare de mai sus.</b></p>

        <table class="no-border mt-12">
            <tr>
                <td style="width: 80%;">Data:</td>
                <td>Nume, prenume</td>
            </tr>
        </table>

        <br>
        <br>

        <p class="text-center"><b>DECLARATIE DE CONSIMTAMANT</b></p>

        <br>

        <p>Subsemnatul/a <b>{{ $numePacient }}</b>, având următoarele date de identificare B.I./C.I. seria {{ $serieBuletin }}, nr. {{ $numarBuletin }}, eliberat/eliberată la data de {{ $pacient->data_eliberare_buletin ? Carbon::parse($pacient->data_eliberare_buletin)->isoFormat('DD.MM.YYYY') : '' }}, <b>îmi exprim consimțământul în mod expres pentru publicarea/postarea în mediul on-line în vederea promovării serviciilor incluse în obiectul de activitate al THERANOVA PROTEZARE, cu scop de marketing, publicitate, promovare și arăt că sunt de acord cu publicarea fotografiilor și filmărilor în orice format conținând imaginea subsemnatului pe pagini/platforme on-line (Facebook, Instagram etc.) deținute/administrate de THERANOVA PROTEZARE. Prezentul consimțământ este valabil până la revocarea expresă a acestuia prin notificarea scrisă a THERANOVA PROTEZARE.</b></p>

        <table class="no-border mt-12">
            <tr>
                <td style="width: 80%;">Data:</td>
                <td>Nume, prenume</td>
            </tr>
        </table>

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
