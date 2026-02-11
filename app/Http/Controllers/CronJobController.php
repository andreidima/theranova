<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use App\Models\FisaCaz;
use App\Models\Incasare;
use App\Models\MesajTrimisEmail;

use App\Mail\OfertaDecizieCasReminder;

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
            // Mail::to(['danatudorache@theranova.ro', 'andrei.dima@usm.ro'])
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

    protected function aFostTrimisReminderDecizieCas(int $decizieId, int $tip): bool
    {
        return MesajTrimisEmail::where('referinta', 4)
            ->where('referinta_id', $decizieId)
            ->where('tip', $tip)
            ->exists();
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
