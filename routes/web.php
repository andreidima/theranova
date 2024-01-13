<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacientController;
use App\Http\Controllers\FisaCazController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FisierController;

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

    Route::resource('/fise-caz/{fisaCaz}/oferte', OfertaController::class)->parameters(['oferte' => 'oferta']);

    Route::resource('/utilizatori', UserController::class)->parameters(['utilizatori' => 'user']);

    Route::get('/fisiere/{fisier}/deschide-descarca', [FisierController::class, 'deschideDescarca']);
});



