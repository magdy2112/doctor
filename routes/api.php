<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function(){

    Route::controller(UserController::class)->group(function(){
        Route::get('/user_index', 'index');
        Route::get('/userprofile/{id}', 'userprofile');
        Route::get('/doctorprofile/{id}', 'doctorprofile');
        Route::post('/find_doctor', 'find_doctor');
        Route::post('/find_doctor_by_specialty', 'find_doctor_by_specialty');
        Route::get('/doctor_category', 'doctor_category');
        Route::get('/all_appointments/{id}', 'all_appointments');
        Route::post('user_reservation','user_reservation');
        Route::post('Available_appointments','Available_appointments');


        // Route::post('/all_appointments/{id}', 'all_appointments');





        // Route::post('/reservation', 'reservation');
        Route::controller(ReservationController::class)->group(function(){


        });
        Route::controller(DoctorController::class )->group(function(){
            Route::get('/doctorprofile', 'doctorprofile');
            Route::post('/update_reservation_status/{id}', 'update_reservation_status');
            Route::post('/set_appoinments', 'set_appoinments');
            Route::put('/cancel_appointment/{id}', 'cancel_appointment');
            Route::get('/get_reservations', 'get_reservations');





        });;


    });

});
//***************************************************** */
    Route::controller(LoginController::class)->group(function(){
        Route::post('/userlogin', 'userlogin');
        Route::post('/doctorLogin', 'doctorLogin');
        Route::post('/userlogout', 'userlogout');
        Route::post('/doctorlogout', 'doctorlogout');
    });
    /////////////////////////////////////////////
Route::controller(RegisterController::class)->group(function(){
    Route::post('/user_register', 'userregister');
    Route::post('/doctor_register', 'doctorregister');


});

//*************************************************************** */


