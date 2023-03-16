<?php

use Illuminate\Support\Facades\Route;
use Modules\Guardian\Http\Controllers\GuardianController;

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

Route::resource('guardian', GuardianController::class);
Route::get('/guardian/profile/{id?}', [GuardianController::class, 'profile'])->name('guardian.profile');