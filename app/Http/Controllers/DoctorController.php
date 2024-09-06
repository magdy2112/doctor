<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\ReservationStatusNotification;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class DoctorController extends Controller
{
use HttpResponse;

    public function doctorprofile(){
        $id=auth()->user()->id;
       $doctor_reservations=  Doctor::with('reservations')->where('id',$id)->get();
        return   $doctor_reservations;


// dd($id);


      }

      public function update_reservation_status(request $request, $id) {
        $reservation = Reservation::find($id);

         $update_reservation_status = $request->validate([
            'status' => 'required|in:confirmed,cancelled',


         ]);
        $doctor_id = $reservation['doctor_id'];
          if( $doctor_id==auth()->user()->id){


            try {
                $reservation->status = $request->input('status');
                $reservation->save();
                $user_id = $reservation->user_id;
                $user = User::find($user_id);
                $user->notify(new ReservationStatusNotification($reservation));
                return $this->response(true, 200, 'Reservation confirmed successfully');
            } catch (\Exception $e) {
                return $this->response(false, 500, 'Error updating reservation status: ' );
            }

          }else{
            return $this->response(false, 403, 'Unauthorized');
          }



    }
}
