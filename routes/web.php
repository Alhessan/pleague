<?php

use App\Http\Controllers\MatchesController;
use App\Http\Controllers\RecordsController;
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

Route::get('/generate',[MatchesController::class, 'generateMatches']);

/*for test --> http://pleague.pr/weekresults?week_id=1&XDEBUG_SESSION_START=PHPSTORM */
Route::get('/weekresults',[MatchesController::class, 'getWeekMatchesResults']);
Route::get('/playall',[MatchesController::class, 'playAllMatches']);
Route::get('/generateRecords',[RecordsController::class, 'generateRecords']);
/* http://pleague.pr/calculateRecords?XDEBUG_SESSION_START=PHPSTORM */
Route::get('/calculateRecords',[RecordsController::class, 'calculateRecords']);
Route::get('/predictions',[RecordsController::class, 'getPredictions']);
