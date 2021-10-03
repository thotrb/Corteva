<?php

use App\Http\Controllers\indexLoginController;
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
/**
Route::get('/', function () {
    return view('welcome');
});

**/
Route::get('/', indexLoginController::class.'@index');

Route::get('/endPO/{productionName}/endPO', \App\Http\Controllers\FormController::class.'@endPO');

Route::get('/{productionName}/plannedDowntime/{downtimeReason}', \App\Http\Controllers\FormController::class.'@plannedDowntime_Event');

Route::get('login/{typeLogin}', indexLoginController::class.'@loginPage');

Route::get('/teamInfo', \App\Http\Controllers\FormController::class.'@index');

Route::get('/packagingLineID', \App\Http\Controllers\FormController::class.'@indexAdmin');

Route::get('/downtimesReport', \App\Http\Controllers\FormController::class.'@downtimesReport');


Route::get('/summary', \App\Http\Controllers\FormController::class.'@summary');

Route::get('/summary/{productionName}', \App\Http\Controllers\FormController::class.'@choicePlannedUnplanned');

Route::get('/summary/{productionName}/{downtimeType}', \App\Http\Controllers\FormController::class.'@downtimeReason_1');

Route::get('/{productionName}/{downtimeType}/unplannedDowntime', \App\Http\Controllers\FormController::class.'@unplannedDowntime_2');

Route::get('/{productionName}/{downtimeType}/CIP', \App\Http\Controllers\FormController::class.'@unplannedDowntime_CIP');

Route::get('/{productionName}/{downtimeType}/changingFormat', \App\Http\Controllers\FormController::class.'@unplannedDowntime_changingFormat');

Route::get('/{productionName}/{downtimeType}/clientChanging', \App\Http\Controllers\FormController::class.'@unplannedDowntime_clientChanging');


