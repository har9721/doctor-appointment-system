<?php

use App\Http\Controllers\HomeController;
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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/get-city',[HomeController::class,'getCity'])->name('get-city');
Route::get('/get-state',[HomeController::class,'getState'])->name('get-state');
Route::get('/get-gender',[HomeController::class,'getGender'])->name('get-gender');
Route::get('/get-smoking-status',[HomeController::class,'getSmokingStatus'])->name('get-smoking-status');
Route::get('/get-alcohol-status',[HomeController::class,'getAlcoholStatus'])->name('get-alcohol-status');
