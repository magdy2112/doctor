<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function(){

    Route::controller(UserController::class)->group(function(){
        Route::get('/user_index', 'index');
        Route::get('/userprofile/{id}', 'userprofile');
        Route::get('/doctorprofile/{id}', 'doctorprofile');
        Route::post('/reservation', 'reservation');


        // Route::put('/updateuserprofile', 'updateuserprofile');
        // Route::put('/updatedoctrofprofile', 'updatedoctrofprofile');
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


