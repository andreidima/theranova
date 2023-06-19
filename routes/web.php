<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RecoltareSangeController;
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
    Route::resource('/recoltari-sange', RecoltareSangeController::class)->parameters(['recoltari-sange' => 'recoltareSange']);
});
