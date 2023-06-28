<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RecoltareSangeController;
use App\Http\Controllers\RecoltareSangeIntrareController;
use App\Http\Controllers\RecoltareSangeComandaController;
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

Auth::routes(['register' => false, 'password.request' => false, 'reset' => false]);


Route::redirect('/', '/acasa');


Route::group(['middleware' => 'auth'], function () {
    Route::view('/acasa', 'acasa');


    Route::get('/recoltari-sange/rebuturi', [RecoltareSangeController::class, 'rebuturi']);
    Route::get('/recoltari-sange/rebuturi/modifica/{recoltareSange}', [RecoltareSangeController::class, 'rebuturiModifica']);
    Route::patch('/recoltari-sange/rebuturi/modifica/{recoltareSange}', [RecoltareSangeController::class, 'postRebuturiModifica']);

    Route::resource('/recoltari-sange/intrari', RecoltareSangeIntrareController::class)->parameters(['intrari' => 'recoltareSangeIntrare']);

    Route::resource('/recoltari-sange/comenzi', RecoltareSangeComandaController::class)->parameters(['comenzi' => 'recoltareSangeComanda']);
    Route::get('/recoltari-sange/comenzi/{recoltareSangeComanda}/{view_type}', [RecoltareSangeComandaController::class, 'exportPdf']);

    Route::resource('/recoltari-sange', RecoltareSangeController::class)->parameters(['recoltari-sange' => 'recoltareSange']);
});
