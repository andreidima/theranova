<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PacientController;
use App\Http\Controllers\FisaCazController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\ComandaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FisierController;
use App\Http\Controllers\ComandaComponentaController;
use App\Http\Controllers\CronJobController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Auth::routes(['register' => false, 'password.request' => false, 'reset' => false]);
Auth::routes();
Route::get('/asteptare-aprobare', [RegisterController::class, 'register']);

Route::redirect('/', '/acasa');

Route::get('/cronjobs/trimite-email/{key}', [CronJobController::class, 'trimiteEmail']);
Route::get('/cronjobs/trimite-mementouri-activitati-calendar/{key}', [CronJobController::class, 'trimiteMementouriActivitatiCalendar']);

Route::group(['middleware' => 'auth'], function () {
    Route::view('/acasa', 'acasa')->name('acasa');

    Route::resource('/pacienti', PacientController::class)->parameters(['pacienti' => 'pacient']);
    Route::resource('/fise-caz', FisaCazController::class)->parameters(['fise-caz' => 'fisaCaz']);
    Route::any('/fise-caz/adauga-resursa/{resursa}', [FisaCazController::class, 'fisaCazAdaugaResursa']);
    Route::get('/fise-caz/{fisaCaz}/stare/{stare}', [FisaCazController::class, 'stare']);
    Route::post('/fise-caz/{fisaCaz}/adauga-modifica-protezare', [FisaCazController::class, 'adaugaModificaProtezare']);
    Route::post('/fise-caz/{fisaCaz}/adauga-modifica-fisa-masuri', [FisaCazController::class, 'adaugaModificaFisaMasuri']);
    // Route::post('/fise-caz/{fisaCaz}/trimite-email-catre-utilizator/{tipEmail}/{user}', [FisaCazController::class, 'trimitePrinEmailCatreUtilizator']);
    Route::post('/fise-caz/{fisaCaz}/trimite-email-catre-utilizatori/{tipEmail}/{comanda?}', [FisaCazController::class, 'trimitePrinEmailCatreUtilizatori']);
    Route::get('/fise-caz/{fisaCaz}/export/contract-pdf', [FisaCazController::class, 'contractPdf']);
    Route::get('/fise-caz/export/toate-html/{dimensiune}', [FisaCazController::class, 'toateHtml'])->middleware('check.export.access');

    Route::resource('/fise-caz/{fisaCaz}/oferte', OfertaController::class)->parameters(['oferte' => 'oferta']);
    Route::resource('/fise-caz/{fisaCaz}/comenzi', ComandaController::class)->parameters(['comenzi' => 'comanda']);
    Route::get('/fise-caz/{fisaCaz}/comenzi/{comanda}/export/pdf', [ComandaController::class, 'exportPdf']);

    // ComenziComponente
    // Route::resource('/fise-caz/{fisaCaz}/comenzi-componente', ComandaComponentaController::class)->parameters(['comenzi-componente' => 'comandaComponenta']);
    Route::get('/fise-caz/{fisaCaz}/comenzi-componente/toate/adauga', [ComandaComponentaController::class, 'toateAdauga']);
    Route::post('/fise-caz/{fisaCaz}/comenzi-componente/toate/adauga', [ComandaComponentaController::class, 'postToateAdauga']);
    Route::get('/fise-caz/{fisaCaz}/comenzi-componente/toate/modifica', [ComandaComponentaController::class, 'toateModifica']);
    Route::patch('/fise-caz/{fisaCaz}/comenzi-componente/toate/modifica', [ComandaComponentaController::class, 'postToateModifica']);
    Route::delete('/fise-caz/{fisaCaz}/comenzi-componente/toate/sterge', [ComandaComponentaController::class, 'postToateSterge']);
    Route::get('/fise-caz/{fisaCaz}/comenzi-componente/export/pdf', [ComandaComponentaController::class, 'toateExport']);

    Route::resource('/utilizatori', UserController::class)->parameters(['utilizatori' => 'user']);

    Route::get('/fisiere/{fisier}/deschide-descarca', [FisierController::class, 'deschideDescarca']);
    Route::delete('/fisiere/{fisier}/sterge', [FisierController::class, 'sterge']);

    Route::get('/calendar/activitati/adauga-la-fisa-caz/{fisaCaz}', [App\Http\Controllers\Calendar\ActivitateController::class, 'create']);
    Route::get('/calendar/mod-afisare-lunar/activitati/', [App\Http\Controllers\Calendar\ActivitateController::class, 'index']);
    Route::resource('/calendar/activitati', App\Http\Controllers\Calendar\ActivitateController::class)->parameters(['activitati' => 'activitate']);


    // To delete everything about $fisaCaz->programare_atelier (database and app code) at 01.06.2024

});



