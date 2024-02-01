<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\FisaCaz;

class CronJobController extends Controller
{
    public function trimiteEmail($key = null){
        if (is_null($keyDB = DB::table('variabile')->where('nume', 'cron_job_key')->get()->first()->valoare ?? null) || is_null($key) || ($keyDB !== $key)) {
            echo 'Cheia pentru Cron Joburi este incorectă!';
            return ;
        }
        // if ($key !== \Config::get('variabile.cron_job_key')){
        //     echo 'Cheia pentru Cron Joburi nu este corectă!';
        //     return ;
        // }
        // $cron = DB::table('users')->get();
    }
}
