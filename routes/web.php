<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacientController;
use App\Http\Controllers\FisaCazController;
use App\Http\Controllers\OfertaController;
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

Route::group(['middleware' => 'auth'], function () {
    Route::view('/acasa', 'acasa');

    Route::resource('/pacienti', PacientController::class)->parameters(['pacienti' => 'pacient']);
    Route::resource('/fise-caz', FisaCazController::class)->parameters(['fise-caz' => 'fisaCaz']);
    Route::any('/fise-caz/adauga-resursa/{resursa}', [FisaCazController::class, 'fisaCazAdaugaResursa']);
    Route::get('/fise-caz/{fisaCaz}/stare/{stare}', [FisaCazController::class, 'stare']);
    Route::post('/fise-caz/{fisaCaz}/adauga-modifica-fisa-masuri', [FisaCazController::class, 'adaugaModificaFisaMasuri']);
    // Route::post('/fise-caz/{fisaCaz}/trimite-email-catre-utilizator/{tipEmail}/{user}', [FisaCazController::class, 'trimitePrinEmailCatreUtilizator']);
    Route::post('/fise-caz/{fisaCaz}/trimite-email-catre-utilizatori/{tipEmail}', [FisaCazController::class, 'trimitePrinEmailCatreUtilizatori']);

    Route::resource('/fise-caz/{fisaCaz}/oferte', OfertaController::class)->parameters(['oferte' => 'oferta']);

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

    Route::get('/cronjobs/trimite-email/{key}', [CronJobController::class, 'trimiteEmail']);

    // de sters 01.02.2024
    // Route::get('/actualizeaza-date', function (){
    //     $fiseCaz = \App\Models\FisaCaz::with('pacient')->whereHas('comenziComponente')->orderBy('data', 'desc')->get();
    //     foreach ($fiseCaz as $fisaCaz){
    //         echo ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '');
    //         echo '<br>';
    //         echo $fisaCaz->comenziComponente->first()->created_at;
    //         echo '<br>';
    //         $fisaCaz->fisa_comanda_data = $fisaCaz->comenziComponente->first()->created_at;
    //         $fisaCaz->save();
    //         // echo $fisaCaz->fisa_comanda_data;
    //         echo '<br>';
    //         echo '<br>';
    //         echo '<br>';
    //         echo '<br>';
    //     }
    // });
});



