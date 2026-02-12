<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use App\Models\FisaCaz;
use App\Models\Incasare;
use App\Models\Oferta;
use App\Models\MesajTrimisEmail;

use App\Mail\OfertaDecizieCasReminder;
use App\Mail\OferteInAsteptareReminder;

class CronJobController extends Controller
{
    public function trimiteEmail($key = null){
        if (is_null($keyDB = DB::table('variabile')->where('nume', 'cron_job_key')->get()->first()->valoare ?? null) || is_null($key) || ($keyDB !== $key)) {
            echo 'Cheia pentru Cron Joburi este incorectă!';
            return ;
        }

        // Mail reminder pentru 'AK provizorie', dupa 8 luni / 'BK provizorie', dupa 5 luni
        $fiseCaz = FisaCaz::with('dateMedicale', 'userVanzari', 'userComercial', 'userTehnic')
            ->where(function ($query) {
                $query->whereDoesntHave('emailReminderAKProvizorie')
                // ->whereHas('dateMedicale', function($query){
                //     $query->where('tip_proteza', 'AK provizorie');
                // })
                ->where('tip_lucrare_solicitata', 'AK provizorie')
                ->whereDate('protezare' ,'<', Carbon::now()->subMonthNoOverflow(8))
                // ->whereDate('protezare' ,'>', Carbon::now()->subMonthNoOverflow(9))
                ->where(function ($query) {
                    $query->whereNull('stare')
                        ->orWhere('stare', 1);
                });
            })
            ->orWhere(function ($query) {
                $query->whereDoesntHave('emailReminderBKProvizorie')
                // ->whereHas('dateMedicale', function($query){
                //     $query->where('tip_proteza', 'BK provizorie');
                // })
                ->where('tip_lucrare_solicitata', 'BK provizorie')
                ->whereDate('protezare' ,'<', Carbon::now()->subMonthNoOverflow(5))
                // ->whereDate('protezare' ,'>', Carbon::now()->subMonthNoOverflow(6))
                ->where(function ($query) {
                    $query->whereNull('stare')
                        ->orWhere('stare', 1);
                });
            })
            ->orderBy('protezare', 'asc')
            // ->take(1)
            ->get();

        foreach ($fiseCaz as $fisaCaz){
            // echo $fisaCaz->id;
            // echo '. ';
            // echo $fisaCaz->protezare;
            // echo ' - ';
            // echo $fisaCaz->pacient->nume ?? null;
            // echo ' - ';
            // echo $fisaCaz->dateMedicale()->first()->tip_proteza ?? null;
            // echo '<br><br>';

            $adreseEmail = [];
            ($fisaCaz->userVanzari->email ?? null) ? array_push($adreseEmail, $fisaCaz->userVanzari->email) : '';
            ($fisaCaz->userComercial->email ?? null) ? array_push($adreseEmail, $fisaCaz->userComercial->email) : '';
            ($fisaCaz->userTehnic->email ?? null) ? array_push($adreseEmail, $fisaCaz->userTehnic->email) : '';

            if (count($adreseEmail) === 0){
                return ;
            }

            $tip_proteza = $fisaCaz->tip_lucrare_solicitata;
            $tipEmail = $tip_proteza;

            Mail::to($adreseEmail)
                ->cc(['danatudorache@theranova.ro', 'adrianples@theranova.ro'])
                // ->send(new \App\Mail\FisaCaz($fisaCaz, $tipEmail, null, null));
            // Mail::to(['danatudorache@theranova.ro'])
                ->send(new \App\Mail\FisaCazReminder($fisaCaz, $tip_proteza));

            $mesajTrimisEmail = \App\Models\MesajTrimisEmail::create([
                'referinta' => 1, // Fisa caz
                'referinta_id' => $fisaCaz->id,
                'referinta2' => null, // User
                'referinta2_id' => null,
                'tip' => ($tip_proteza == "AK provizorie") ? 5 : (($tip_proteza == "BK provizorie") ? 6 : null) , // reminder AK provizorie
                'mesaj' => '',
                'email' => implode(', ', $adreseEmail)
            ]);
        }
    }

    public function trimiteMementouriActivitatiCalendar($key = null){
        if (is_null($keyDB = DB::table('variabile')->where('nume', 'cron_job_key')->get()->first()->valoare ?? null) || is_null($key) || ($keyDB !== $key)) {
            echo 'Cheia pentru Cron Joburi este incorectă!';
            return ;
        }

        $activitati = \App\Models\Calendar\Activitate::
            whereNotNull('mementouri_zile')
            ->whereDate('data_inceput', '>=', Carbon::today())
            ->get();

        $arrayIdActivitatiDeTrimisMesaj = [];
        foreach ($activitati as $activitate){
            $zileInainte = preg_split ("/\,/", $activitate->mementouri_zile);
            foreach ($zileInainte as $ziInainte){
                if (is_int($ziInainte = intval($ziInainte) )) {
                    if (Carbon::parse($activitate->data_inceput)->startOfDay()->subDays($ziInainte)->eq(Carbon::today())){
                        array_push($arrayIdActivitatiDeTrimisMesaj, $activitate->id);
                    }
                }
            }
        }

        $activitatiDeTrimisMesaj = \App\Models\Calendar\Activitate::with('fisaCaz')->whereIn('id', $arrayIdActivitatiDeTrimisMesaj)->get();

        // Daca nu este nici un memento de trimis pentru ziua curenta, se termina functia
        if (count($activitatiDeTrimisMesaj) === 0){
            return;
        }

        // Trimitere email
        foreach ($activitatiDeTrimisMesaj as $activitate){
            if (isset($activitate->mementouri_emailuri)){
                $emails = array_map('trim', explode(',', $activitate->mementouri_emailuri));
                $validator = Validator::make(['emails' => $emails], ['emails.*' => 'required|email:rfc,dns']);
                if ($validator->fails()) {
                    echo 'Nu toate emailurile sunt corecte';
                    return;
                }

                // Trimitere memento prin email
                \Mail::to($emails)
                    ->send(new \App\Mail\MementoActivitateCalendar($activitate)
                );

                $mesajTrimisEmail = \App\Models\MesajTrimisEmail::create([
                    'referinta' => 3, // Calendar Activitate
                    'referinta_id' => $activitate->id,
                    'referinta2' => null, // User
                    'referinta2_id' => null,
                    'tip' => 8 , // memento Activitate Calendar
                    'mesaj' => '',
                    'email' => $activitate->mementouri_emailuri
                ]);

                echo 'Memento trimis catre: ' . implode(', ', $emails);
                echo '<br><br>';
            }
        }
    }

    public function trimiteReminderDeciziiCas($key = null)
    {
        if (is_null($keyDB = DB::table('variabile')->where('nume', 'cron_job_key')->get()->first()->valoare ?? null) || is_null($key) || ($keyDB !== $key)) {
            echo 'Cheia pentru Cron Joburi este incorectă!';
            return ;
        }

        echo 'Cron job: trimite remindere pentru deciziile CAS fără «data validare». Folosiți acest link doar pentru teste manuale.<br><br>';

        $decizii = Incasare::with(['oferta.fisaCaz.pacient', 'oferta.fisaCaz.userVanzari', 'oferta.fisaCaz.userComercial', 'oferta.fisaCaz.userTehnic'])
            ->where('tip', Incasare::TIP_DECIZIE_CAS)
            ->whereNotNull('data_inregistrare')
            ->whereNull('data_validare')
            ->get();

        if ($decizii->isEmpty()) {
            echo 'Nu există decizii CAS eligibile în acest moment.';

            return;
        }

        $primulReminderTrimis = 0;
        $alDoileaReminderTrimis = 0;
        $deciziiSarit = 0;

        foreach ($decizii as $decizie) {
            if (!$decizie->oferta || !$decizie->oferta->fisaCaz) {
                echo 'Decizie #' . $decizie->id . ' (oferta #' . ($decizie->oferta_id ?? 'n/a') . '): nu are ofertă/fisa caz asociată. Sar peste.<br>';
                $deciziiSarit++;

                continue;
            }

            $fisaCaz = $decizie->oferta->fisaCaz;

            $adreseEmail = collect([
                $fisaCaz->userVanzari->email ?? null,
                $fisaCaz->userComercial->email ?? null,
                $fisaCaz->userTehnic->email ?? null,
            ])->filter()->unique();

            if ($adreseEmail->isEmpty()) {
                echo 'Decizie #' . $decizie->id . ' (oferta #' . $decizie->oferta_id . '): nu există destinatari validați. Sar peste.<br>';
                $deciziiSarit++;

                continue;
            }

            try {
                $dataInregistrare = Carbon::createFromFormat('d.m.Y', $decizie->data_inregistrare);
            } catch (\Exception $exception) {
                echo 'Decizie #' . $decizie->id . ' (oferta #' . $decizie->oferta_id . '): data înregistrare („' . $decizie->data_inregistrare . '”) nu poate fi interpretată. Sar peste.<br>';
                $deciziiSarit++;

                continue;
            }

            $primaTrimitere = (clone $dataInregistrare)->addMonthsNoOverflow(2);
            $aDouaTrimitere = (clone $primaTrimitere)->addDays(15);

            $mesajPrefix = 'Decizie #' . $decizie->id . ' (oferta #' . $decizie->oferta_id . '): ';
            $trimis = false;

            if (!$this->aFostTrimisReminderDecizieCas($decizie->id, 9)) {
                if (Carbon::now()->greaterThanOrEqualTo($primaTrimitere)) {
                    $this->trimiteReminderDecizieCas(
                        $adreseEmail->all(),
                        $decizie,
                        'primul reminder (2 luni)',
                        9
                    );

                    echo $mesajPrefix . 'am trimis primul reminder către: ' . implode(', ', $adreseEmail->all()) . '.<br>';
                    $primulReminderTrimis++;
                    $trimis = true;
                } else {
                    echo $mesajPrefix . 'primul reminder nu este încă scadent. Se va trimite după ' . $primaTrimitere->format('d.m.Y') . '.<br>';
                }
            } else {
                echo $mesajPrefix . 'primul reminder a fost deja trimis anterior.<br>';
            }

            if ($trimis) {
                continue;
            }

            if (!$this->aFostTrimisReminderDecizieCas($decizie->id, 10)) {
                if (Carbon::now()->greaterThanOrEqualTo($aDouaTrimitere)) {
                    $this->trimiteReminderDecizieCas(
                        $adreseEmail->all(),
                        $decizie,
                        'al doilea reminder (2 luni și jumătate)',
                        10
                    );

                    echo $mesajPrefix . 'am trimis al doilea reminder către: ' . implode(', ', $adreseEmail->all()) . '.<br>';
                    $alDoileaReminderTrimis++;
                    $trimis = true;
                } else {
                    echo $mesajPrefix . 'al doilea reminder nu este încă scadent. Se va trimite după ' . $aDouaTrimitere->format('d.m.Y') . '.<br>';
                }
            } else {
                echo $mesajPrefix . 'al doilea reminder a fost deja trimis anterior.<br>';
            }

            if (!$trimis) {
                $deciziiSarit++;
            }
        }

        echo '<br>Rezumat: ' . $primulReminderTrimis . ' prim reminder(e) trimise, ' . $alDoileaReminderTrimis . ' al doilea reminder(e) trimise. ';
        echo $deciziiSarit . ' decizii nu au necesitat acțiuni suplimentare.<br>';
    }

    public function trimiteReminderOferteInAsteptare($key = null)
    {
        if (is_null($keyDB = DB::table('variabile')->where('nume', 'cron_job_key')->get()->first()->valoare ?? null) || is_null($key) || ($keyDB !== $key)) {
            echo 'Cheia pentru Cron Joburi este incorectÄƒ!';
            return;
        }

        echo 'Cron job: trimite remindere pentru ofertele in asteptare mai vechi de 3 luni.<br><br>';

        $milestones = $this->milestonesReminderOferteInAsteptare();
        $tipRetrimitere = $this->tipReminderOfertaInAsteptareRetrimitere();
        $zileRetrimitere = $this->intervalRetrimitereReminderOfertaInAsteptareZile();
        $tipuriInitiale = collect($milestones)->pluck('tip')->map(fn ($tip) => (int) $tip)->all();
        $tipuriReminder = array_values(array_unique(array_merge($tipuriInitiale, [$tipRetrimitere])));
        $minMonths = collect($milestones)->min('months') ?? 3;

        $oferte = Oferta::with(['fisaCaz.pacient', 'fisaCaz.userVanzari', 'fisaCaz.userComercial', 'fisaCaz.userTehnic'])
            ->where('acceptata', Oferta::STATUS_IN_ASTEPTARE)
            ->whereNotNull('created_at')
            ->where('created_at', '<=', Carbon::now()->subMonthsNoOverflow($minMonths))
            ->orderBy('created_at', 'asc')
            ->get();

        if ($oferte->isEmpty()) {
            echo 'Nu exista oferte eligibile in acest moment.';

            return;
        }

        $groups = [];
        $oferteSarite = 0;

        foreach ($oferte as $oferta) {
            if (!$oferta->fisaCaz || !$oferta->fisaCaz->pacient) {
                echo 'Oferta #' . $oferta->id . ': nu are fisa caz/pacient asociat. Sar peste.<br>';
                $oferteSarite++;

                continue;
            }

            $emailuri = collect([
                $oferta->fisaCaz->userVanzari->email ?? null,
                $oferta->fisaCaz->userComercial->email ?? null,
                $oferta->fisaCaz->userTehnic->email ?? null,
            ])->filter()->unique()->sort()->values();

            if ($emailuri->isEmpty()) {
                echo 'Oferta #' . $oferta->id . ': nu are destinatari validi. Sar peste.<br>';
                $oferteSarite++;

                continue;
            }

            $createdAt = Carbon::parse($oferta->created_at);
            $tipDeTrimis = null;
            $labelDeTrimis = null;

            foreach ($milestones as $milestone) {
                $dueAt = (clone $createdAt)->addMonthsNoOverflow($milestone['months']);
                if (Carbon::now()->lt($dueAt)) {
                    continue;
                }

                if ($this->aFostTrimisReminderOfertaInAsteptare($oferta->id, $milestone['tip'])) {
                    continue;
                }

                $tipDeTrimis = (int) $milestone['tip'];
                $labelDeTrimis = $milestone['label'];
                break;
            }

            if (is_null($tipDeTrimis)) {
                $ultimulReminder = $this->ultimulReminderOfertaInAsteptare($oferta->id, $tipuriReminder);
                if ($ultimulReminder) {
                    $dataUltimReminder = Carbon::parse($ultimulReminder->created_at);
                    if ($dataUltimReminder->lte(Carbon::now()->subDays($zileRetrimitere))) {
                        $tipDeTrimis = $tipRetrimitere;
                        $labelDeTrimis = 'retrimis dupa ' . $zileRetrimitere . ' zile';
                    }
                }
            }

            if (is_null($tipDeTrimis)) {
                $oferteSarite++;
                continue;
            }

            // Grouping by exact recipient set minimizes number of sent emails.
            $groupKey = implode('|', $emailuri->all());
            if (!isset($groups[$groupKey])) {
                $groups[$groupKey] = [
                    'emails' => $emailuri->all(),
                    'oferte' => [],
                ];
            }

            $groups[$groupKey]['oferte'][$oferta->id] = [
                'id' => $oferta->id,
                'tip' => $tipDeTrimis,
                'label' => $labelDeTrimis,
                'pacient' => trim(($oferta->fisaCaz->pacient->nume ?? '') . ' ' . ($oferta->fisaCaz->pacient->prenume ?? '')),
                'link_modificare' => url($oferta->path() . '/modifica'),
                'created_at' => $createdAt->format('d.m.Y'),
                'vechime_zile' => $createdAt->diffInDays(Carbon::now()),
            ];
        }

        if (empty($groups)) {
            echo 'Nu exista oferte de notificat (toate sunt deja notificate sau neeligibile).';

            return;
        }

        $emailuriTrimise = 0;
        $oferteNotificate = 0;
        $oferteNotificateInitial = 0;
        $oferteNotificateRetrimise = 0;

        foreach ($groups as $group) {
            $oferteDeTrimis = collect(array_values($group['oferte']));

            Mail::to($group['emails'])
                ->cc(['danatudorache@theranova.ro', 'adrianples@theranova.ro'])
                ->send(new OferteInAsteptareReminder($oferteDeTrimis));

            foreach ($oferteDeTrimis as $ofertaInfo) {
                MesajTrimisEmail::create([
                    'referinta' => 5, // Oferta
                    'referinta_id' => $ofertaInfo['id'],
                    'referinta2' => null,
                    'referinta2_id' => null,
                    'tip' => $ofertaInfo['tip'],
                    'mesaj' => $ofertaInfo['label'],
                    'email' => implode(', ', $group['emails']),
                ]);

                if ((int) $ofertaInfo['tip'] === $tipRetrimitere) {
                    $oferteNotificateRetrimise++;
                } else {
                    $oferteNotificateInitial++;
                }

                $oferteNotificate++;
            }

            $emailuriTrimise++;
            echo 'Am trimis reminder catre: ' . implode(', ', $group['emails']) . ' pentru ' . $oferteDeTrimis->count() . ' oferta(e).<br>';
        }

        echo '<br>Rezumat: ' . $emailuriTrimise . ' email(uri) trimise, ' . $oferteNotificate . ' oferta(e) notificate. ';
        echo $oferteNotificateInitial . ' initiale, ' . $oferteNotificateRetrimise . ' retrimise. ';
        echo $oferteSarite . ' oferta(e) fara actiuni suplimentare.<br>';
    }

    protected function aFostTrimisReminderDecizieCas(int $decizieId, int $tip): bool
    {
        return MesajTrimisEmail::where('referinta', 4)
            ->where('referinta_id', $decizieId)
            ->where('tip', $tip)
            ->exists();
    }

    protected function milestonesReminderOferteInAsteptare(): array
    {
        return [
            [
                'months' => 3,
                'tip' => 11,
                'label' => 'primul reminder (3 luni)',
            ],
        ];
    }

    protected function aFostTrimisReminderOfertaInAsteptare(int $ofertaId, int $tip): bool
    {
        return MesajTrimisEmail::where('referinta', 5)
            ->where('referinta_id', $ofertaId)
            ->where('tip', $tip)
            ->exists();
    }

    protected function tipReminderOfertaInAsteptareRetrimitere(): int
    {
        return 12;
    }

    protected function intervalRetrimitereReminderOfertaInAsteptareZile(): int
    {
        return 7;
    }

    protected function ultimulReminderOfertaInAsteptare(int $ofertaId, array $tipuri): ?MesajTrimisEmail
    {
        return MesajTrimisEmail::where('referinta', 5)
            ->where('referinta_id', $ofertaId)
            ->whereIn('tip', $tipuri)
            ->orderByDesc('created_at')
            ->first();
    }

    protected function trimiteReminderDecizieCas(array $emailuri, Incasare $decizie, string $tipReminder, int $tipCod): void
    {
        Mail::to($emailuri)
            ->cc(['danatudorache@theranova.ro', 'adrianples@theranova.ro'])
            ->send(new OfertaDecizieCasReminder($decizie, $tipReminder));

        MesajTrimisEmail::create([
            'referinta' => 4,
            'referinta_id' => $decizie->id,
            'referinta2' => null,
            'referinta2_id' => null,
            'tip' => $tipCod,
            'mesaj' => '',
            'email' => implode(', ', $emailuri),
        ]);
    }
}
