<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/game', function () {
    return view('game');
});

//Route::get('/new-game', 'App\Http\Controllers\GameController@generateNumber');
Route::post('/new-game', [\App\Http\Controllers\GameController::class, 'newGame']);

Route::get('/check/{guess}', [\App\Http\Controllers\GameController::class, 'checkNumber']);

Route::get('/give-up', [\App\Http\Controllers\GameController::class, 'giveUp']);

Route::get('/edit-name/{name}', [\App\Http\Controllers\GameController::class, 'editName']);

Route::get('/get-top/{category}', [\App\Http\Controllers\GameController::class, 'getTop']);
