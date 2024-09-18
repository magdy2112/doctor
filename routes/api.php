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
use App\Http\Controllers\CityController;
use App\Http\Controllers\QualificationController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\UserController;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//    'auth:sanctum'

  //***************************************************  start doctor  **************************************************************************************************************/

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
        Route::post('update_doctor_profile','update_doctor_profile');

    })->middleware( 'auth:sanctum');;

    //***************************************************  END DOCTOR  ************************************************************************************************************/




//*********************************************  START USER  ***********************************************************************************************************************/

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
        Route::get('/userprofile', 'userprofile')->middleware( 'auth:sanctum');
        Route::post('/find_doctor_name', 'find_doctor_name');
        Route::post('/find_doctor_by_specialty', 'find_doctor_by_specialty');
        Route::get('/doctor_category', 'doctor_category');

      });


//*********************************************  END USER ************************************************************************************************************/




     //*********************************************  START APPOINMENTS ***********************************************************************************************/

            Route::controller(AppointmentsController::class)->group(function(){
                 Route::post('/doctor_set_appoinments', 'doctor_set_appoinments');
                 Route::post('/doctor_cancel_appointment', 'doctor_cancel_appointment');
                 Route::post('/doctor_update_Appoinment', 'doctor_update_Appoinment');
             })->middleware( 'auth:sanctum');

     //*********************************************  END APPOINMENTS **********************************************************************************************************/





     //*********************************************  START CITY *****************************************************************************************************/

       Route::controller(CityController::class)->group(function(){
         Route::get('/all_city', 'all_city');
         Route::post('/doctor_city', 'doctor_city');
       });

     //*********************************************  END CITY *****************************************************************************************************/


    //*********************************************  START QUALIFICATION ***************************************************************************************************/
      Route::controller( QualificationController::class)->group(function(){
         Route::get('/all_qualifications','all_qualifications');
         Route::post('/doctor_qualifications','doctor_qualifications');

      });

     //*********************************************  END QUALIFICATION ********************************************************************************************************/




    //*********************************************  START RESEVATION*******************************************************************************************************/
        Route::controller(ReservationController::class)->group(function(){
            Route::get('/get_all_doctor_reservations','get_all_doctor_reservations');
            Route::post('/get_special_doctor_reservation','get_special_doctor_reservation');
            Route::get('/get_all_user_reservation','get_all_user_reservation');
            Route::post('/user_cancel_reservation','user_cancel_reservation');
            Route::post('/user_reservation','user_reservation');
        })->middleware('auth:sanctum');

     //*********************************************  END RESEVATION ****************************************************************************************************/





    //*********************************************  START Specializations********************************************************************************************/

    Route::controller(SpecializationController::class)->group(function(){
        Route::get('/all_specializations','all_specializations');
        Route::post('/doctor_specializations','doctor_specializations');
    });

    //*********************************************  END Specializations***********************************************************************************************/

