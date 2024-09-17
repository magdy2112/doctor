<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\ReservationNotification;
use App\Notifications\ReservationStatusNotification;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ReservationController extends Controller
{
    use HttpResponse;

    public function get_reservations(){

        $id= Auth::guard('doctor')->user()->id;
        $today= Carbon::today();

       $allreservations= Reservation::where('doctor_id',$id)
       ->where('created_at','>=',$today)->with('appointment')->get();
       $allreservations_count= Reservation::where('doctor_id',$id)
       ->where('created_at','>=',$today)->with('appointment')->count();

              if (  $allreservations) {
                  $allreservations->makeHidden(['user_id','doctor_id','appointment_id','id','status','created_at','updated_at']);
                  return $this->response(true, 200, 'ok',      ['all_reservation'=>$allreservations,'all_reservation_count'=>$allreservations_count]);
              }else{
                  return $this->response(false, 404, 'No reservations found');
              }
  }
}








