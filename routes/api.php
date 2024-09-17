<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\auth_user\LoginController;
use App\Http\Controllers\auth_user\RegisterController;
use App\Http\Controllers\auth_doctor\LoginDoctorController;
use App\Http\Controllers\auth_doctor\LogOutDoctorController;
use App\Http\Controllers\auth_doctor\PassWordDoctorController;
use App\Http\Controllers\auth_doctor\RegisterDoctorController;
use App\Http\Controllers\auth_user\PassWordController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\auth_user\LogOutController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//    'auth:sanctum'

  //***************************************************  start doctor  ***********************************************************************/

  Route::controller(LoginDoctorController::class)
  ->group(function(){
      Route::post('/doctorLogin', 'doctorLogin')->middleware('guest');
   });

                 /********************************************************************** */

 Route::controller(LogOutDoctorController::class)
 ->group(function(){
     Route::delete('/doctorlogout', 'doctorlogout');
 })->middleware( 'auth:sanctum');

                /********************************************************************** */

  Route::controller(RegisterDoctorController::class)
     ->group(function(){
     Route::post('/doctorregister', 'doctorregister')->middleware('guest');

 });

               /********************************************************************** */

 Route::controller(PassWordDoctorController::class)->group(function(){
     Route::post('/change_doctor_password', 'change_doctor_password')->middleware('auth:sanctum');
     Route::post('/doctor_forget_password', 'doctor_forget_password')->name('password')->middleware('guest');
     Route::post('/Login_new_password', 'Login_new_password')->middleware('guest');


 });

                /********************************************************************** */

    Route::controller(DoctorController::class)->group(function(){
        Route::get('/doctorprofile', 'doctorprofile');
        Route::post('update_DoctorPassword','update_DoctorPassword');
    })->middleware( 'auth:sanctum');;

    //***************************************************  END DOCTOR  ***********************************************************************/




      //*********************************************  START USER  *******************************************************************/

      Route::controller(LoginController::class)->group(function(){
          Route::post('/userlogin', 'userlogin')->middleware('guest');
       });

                     /********************************************************************** */

     Route::controller(LogOutController::class)->group(function(){
         Route::delete('/userlogout', 'userlogout')->middleware( 'auth:sanctum');;
     });

                     /********************************************************************** */

    Route::controller(PassWordController::class)->group(function(){
         Route::post('/change_user_password', 'change_user_password')->middleware('auth:sanctum');
         Route::post('/user_forget_password', 'user_forget_password')->name('passwordd')->middleware('guest');
         Route::post('/Login_new_password', 'Login_new_password')->middleware('guest');

    });
                /********************************************************************** */
     Route::controller(RegisterController::class)->group(function(){
                    Route::post('/user_register', 'user_register')->middleware('guest');

                 });

                /********************************************************************** */


      Route::controller(UserController::class)->group(function(){
        Route::get('/user_home', 'user_home');
        Route::post('/doctorprofile', 'doctorprofile');
        Route::get('/userprofile', 'userprofile');
        Route::post('/find_doctor', 'find_doctor_name');
        Route::post('/find_doctor_by_specialty', 'find_doctor_by_specialty');
        Route::get('/doctor_category', 'doctor_category');

      })->middleware( 'auth:sanctum');;


      //*********************************************  END USER *******************************************************************/


        //*********************************************  START APPOINMENTS *******************************************************************/


            Route::controller(AppointmentsController::class)->group(function(){
                 Route::post('/doctor_set_appoinments', 'doctor_set_appoinments');
                 Route::post('/doctor_cancel_appointment', 'doctor_cancel_appointment');

             });



















      //***************end user***********************************/


















    // Route::controller(UserController::class)->group(function(){
    //     Route::get('/user_index', 'index');
    //     Route::get('/userprofile/{id}', 'userprofile');
    //     Route::get('/doctorprofile/{id}', 'doctorprofile');
    //     Route::post('/find_doctor', 'find_doctor');
    //     Route::post('/find_doctor_by_specialty', 'find_doctor_by_specialty');
    //     Route::get('/doctor_category', 'doctor_category');
    //     Route::get('/all_appointments/{id}', 'all_appointments');
    //     Route::post('user_reservation','user_reservation');
    //     Route::post('Available_appointments','Available_appointments');
    //     Route::post('update_user_password','update_password');

    //      //end user





           //start auth_doctor

           //start Appointments

            // Route::controller(AppointmentsController::class)->group(function(){
            //     Route::post('/set_appoinments', 'set_appoinments');
            // Route::put('/cancel_appointment/{id}', 'cancel_appointment');

            // });



           //end appoinment

           //start reservation
            // Route::controller(ReservationController::class)->group(function(){
            //     Route::post('/user_reservation','user_reservation');
            //     Route::get('/Available_appointments','Available_appointments');
            // });
            //end reservation



            //start notification
//             Route::controller(NotificationController::class)->group(function(){
//                 Route::get('/get_notification','get_notification');
// //             });
//             //end notification


//         });;


//     });

// });

//end sanctum
/*************************************************************************************************************************** */
//doctor

/****************************************************************************************** */
// Route::controller(LoginDoctorController::class)
// ->group(function(){
//     Route::post('/doctorLogin', 'doctorLogin')->middleware('guest');

// });



























//***************************************************** */
    // Route::controller(LoginController::class)->group(function(){
    //     Route::post('/userlogin', 'userlogin');
    //     Route::post('/doctorLogin', 'doctorLogin');
    //     Route::delete('/userlogout', 'userlogout')->middleware('auth:sanctum');
    //     // Route::delete('/doctorlogout', 'doctorlogout')->middleware('auth:sanctum');
    // });
    /////////////////////////////////////////////
// Route::controller(RegisterController::class)->group(function(){
//     Route::post('/user_register', 'userregister');
//     Route::post('/doctor_register', 'doctorregister');


// });



//*************************************************************** */

// Route::controller(UserController::class)->group(function(){
//     Route::post('/forget_password', 'forget_password')->name('password')->middleware('guest');
//     Route::post('get_new_password','get_new_password')->middleware('guest');

// });


