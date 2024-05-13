<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use App\Models\FisaCaz;

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
                    exit;
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
}
