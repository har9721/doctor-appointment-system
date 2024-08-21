<?php

use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PatientsController;
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

Route::group(['prefix' => '/admin', 'as' => 'admin.'], function(){
    Route::controller(PatientsController::class)->group(function(){
        Route::get('/patients','patientView')->name('patients');
        Route::get('/get-all-patients','getAllPatients')->name('get-patients');
    });

    Route::controller(DoctorController::class)->group(function(){
        Route::get('/doctor','doctorView')->name('doctor');
        Route::get('add-doctor','addDoctor')->name('add-doctor');
        Route::post('register-doctor','doctorRegistration')->name('doctorRegister');
        Route::get('/get-doctor-list','fetchAllDoctorList')->name('doctor-list');
        Route::get('specialty','getAllSpecialty')->name('specialty');
        Route::post('save-specialty','saveSpecialty')->name('save-specialty');
        Route::get('fetch-specialty','fetchAllSpecialty')->name('get-specialty');
        Route::get('get-specialty','fetchSpecialtyList')->name('specialtyList');
    });

});

