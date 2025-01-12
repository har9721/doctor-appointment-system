<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\PaymentController;
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
})->name('/');

Auth::routes();

Route::get('/get-city',[HomeController::class,'getCity'])->name('get-city');
Route::get('/get-state',[HomeController::class,'getState'])->name('get-state');
Route::get('/get-gender',[HomeController::class,'getGender'])->name('get-gender');
Route::get('/get-smoking-status',[HomeController::class,'getSmokingStatus'])->name('get-smoking-status');
Route::get('/get-alcohol-status',[HomeController::class,'getAlcoholStatus'])->name('get-alcohol-status');

Route::middleware('auth')->group(function(){

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::group(['prefix' => '/admin', 'as' => 'admin.'], function(){
        Route::controller(PatientsController::class)->group(function(){
            Route::get('/patients','patientView')->name('patients');
            Route::get('/get-all-patients','getAllPatients')->name('get-patients');
            Route::get('/edit-patients-details/{patients}','editPatient')->name('edit-patients');
            Route::post('/save-patients-details','updatePatientsDetails')->name('patientsUpdate');
            Route::get('/view-patient-history/{patients}','viewPatientsHistory')->name('view-patient-history');
        });

        Route::controller(HomeController::class)->group(function(){
            Route::get('profile/{user}','profileView')->name('profile');
            Route::post('update-user-info','updateUserDetails')->name('upateUserDetails');
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
            Route::post('delete-specialty','deleteSpecialty')->name('delete-specialty');
            Route::get('/edit-doctor-details/{doctor}','editDoctorForm')->name('editDoctorDetails');
            Route::post('/update-doctor-details','doctorUpdate')->name('doctorUpdate');
            Route::post('/delete-doctor','deleteDoctor')->name('deleteDoctor');
            Route::get('/edit-doctor-details','getEditForm')->name('editDoctor');
        });
    });

    Route::group(['prefix' => '/doctor', 'as' => 'doctor.'], function(){
        Route::controller(DoctorController::class)->group(function(){
            Route::get('time-slot','viewTimeSlot')->name('time-slot');
            Route::get('fetch-time-slot-list','getTimeSlot')->name('getTimeSlot');
            Route::post('add-time-slot','addTimeSlot')->name('addTimeSlot');
            Route::post('delete-time-slot','deleteTimeSlot')->name('deleteTimeSlot');
            Route::post('update-time-slot','updateTimeSlot')->name('updateTimeSlot');
            Route::get('/get-doctor-list','getAllDoctorList')->name('get-all-doctor');
            Route::get('/fetch-time-slot','fetchTimeSlotForDate')->name('fetch-time-slot');
        });
    });

    Route::group(['prefix' => '/patients', 'as' => 'patients.'], function(){
        Route::controller(PatientsController::class)->group(function(){
            Route::get('appointment-booking','viewAppointmentBookingPage')->name('appointment-booking');
            Route::get('search-doctor','searchDoctor')->name('search-doctor');
            Route::post('book-appointment','bookAppointment')->name('book-appointment');
            Route::get('/get-patients-list','getAllPatientsList')->name('get-all-patients');
        });
    });

    //appointments related routes
    Route::group(['prefix' => '/appointments', 'as' => 'appointments.'],function(){
        Route::controller(AppointmentController::class)->group(function(){
            Route::get('my-appointment','getAppointments')->name('my-appointments');
            Route::post('mark-appoitment','makrAppointments')->name('mark-appoitment');
            Route::get('fetch-appointment-details','getAppointmentsDetails')->name('get-appointments-details');
            Route::get('get-doctor-available-time-slot','getDoctorAvailableTime')->name('fetch-time-slot');
            Route::get('reschedule-appointment-details','rescheduleAppointment')->name('reschedule-appoitment');
            Route::post('save-amount','saveAmount')->name('save-amount');
            Route::get('completed-appointment-list','viewCompletedList')->name('completed-list');
            Route::get('get-completed-appointment','getCompletedAppointment')->name('get-appointment-completed-list');
            Route::get('get-appointment-details','getAppointmentDetails')->name('get-appointment-details');
        });
    });

    // payment related routes
    Route::group(['prefix' => '/payments', 'as' => 'payments.'], function(){
        Route::controller(PaymentController::class)->group(function(){
            Route::get('payment-page/{appointments}','viewPaymentPage')->name('payment-page');
            Route::post('save-payment','processPayment')->name('save-payment');
            Route::get('get-payment-summary','fetchPaymentSummary')->name('fetch-payment-summary');
            Route::get('/success-page','showPaymentSuccess')->name('success-page');
        });
    });
});

Route::post('/payment/success', [PaymentController::class, 'handlePayment'])->name('payment.success');

// forgot password
Route::get('forgot-password',[ForgotPasswordController::class,'index'])->name('forgot-password');
Route::post('forgot-password',[ForgotPasswordController::class,'forgetPassword'])->name('forgot-password');
Route::get('reset-password/{token}',[ForgotPasswordController::class,'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password',[ForgotPasswordController::class,'submitResetPasswordForm'])->name('reset.password.post');