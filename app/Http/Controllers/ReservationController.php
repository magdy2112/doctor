<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\ReservationNotification;
use App\Notifications\ReservationStatusNotification;
use App\Traits\HttpResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ReservationController extends Controller
{
    use HttpResponse;

  public function get_all_doctor_reservations(){
    try {
        $id = Auth::guard('doctor')->user()->id;
        $allreservations = Reservation::where('doctor_id', $id)->with('appointment')->get();
        $allreservations_count = $allreservations->count();

        if ($allreservations) {
            $allreservations->makeHidden(['user_id', 'doctor_id', 'appointment_id', 'id', 'status', 'created_at', 'updated_at']);
            return $this->response(true, 200, 'ok', ['all_reservation' => $allreservations, 'all_reservation_count' => $allreservations_count]);
        } else {
            return $this->response(false, 404, 'No reservations found');
        }
    } catch (\Exception ) {
        return $this->response(false, 500, 'Unauthorized');
    }
} // end method

/************************************************************************************************* */
  public function get_special_doctor_reservation(Request $request){
    $request->validate([
       'appointment_id' =>'required|exists:specializations,id'
    ]);
    $id=Auth::guard('doctor')->user()->id;
   $allreservations= Reservation::where('doctor_id',$id)
   ->where('appointment_id',$request->input('appointment_id'))->get();
  if (  $allreservations) {
  $allreservations->makeHidden(['user_id','doctor_id','appointment_id','id','created_at','updated_at']);
  return $this->response(true, 200, 'ok',      ['all_reservation_related_appointments'=> $allreservations]);
  }else{
    return $this->response(false, 404, 'No reservations found');
  }

  }// end method


  /************************************************************************************************* */

  public function get_all_user_reservation(){
    try {
        $id = Auth::guard('user')->user()->id;
        // $allreservations = Reservation::where([
        //     'user_id' => $id,
        //     'status' => 'confirmed'
        // ])->with('appointment')->get();

        $allreservations =Reservation::where('user_id',$id)->with('appointment.doctor')->get();
        return $allreservations;

        if ($allreservations) {
            return $this->response(true, 200, 'ok', ['all_reservation' => $allreservations]);
        } else {
            return $this->response(false, 404, 'No reservations found');
        }
    } catch (\Exception ) {
        return $this->response(false, 500, 'Unauthorized');
}

}// end method


/************************************************************************************************* */

public function user_cancel_reservation(Request $request){
    try{

        $userrequest= $request->validate([
            'reservation_id' =>'required|exists:reservations,id',
            'user_id'=>''
        ]);


        $id= Auth::guard('user')->user()->id;
        // dd($id);
        $userrequest['user_id'] =  $id;

        $reservation = Reservation::where([
            'user_id' => $id,
            'id' => $request->input('reservation_id'),
           'status' => 'confirmed'
        ])->first();
        //    return $reservation;
        $appointment = Appointment::where('id', $reservation->appointment_id)
        ->where('status', '<>', 'cancelled')
        ->first();
        // return $appointment->count;
        if ($reservation && $appointment) {
            // $reservation->delete();
            $reservation->update(['status' => 'cancelled']);
            $appointment->count-=1;
            $appointment->save();

            return $this->response(true, 200, 'Appointment canceled successfully', $reservation);
        } else {
            return $this->response(false, 404, 'Appointment not found or you are not authorized to updated it');
        }

    }catch(Exception $e){
        return $this->response(false, 404, 'Error canceling appointment: ' . $e->getMessage());
    }

}// end method


/************************************************************************************************* */

    public function user_reservation(Request $request)
    {
        $user = User::find(Auth::guard('user')->user()->id);
        if ($user) {
            $reservation_request = $request->validate([
                'doctor_id' => 'required|integer|exists:doctors,id',
                'appointment_id' => 'required|exists:appointments,id' ,
                'user_id' => '',
            ]);

            $reservation_request['doctor_id'] = request()->input('doctor_id');
            $reservation_request['user_id'] = $user->id;
            $reservation_request['appointment_id'] = request()->input('appointment_id');

            $doctor = Doctor::find($reservation_request['doctor_id'])->where('status', 'active')->first();
            $appointment = Appointment::where('id', request()->input('appointment_id'))->first();
            $maxCount = $appointment->max_patients;

            // Check if the user has already made a reservation for the same appointment and doctor
            $existingReservation = Reservation::where('doctor_id', $reservation_request['doctor_id'])
                ->where('user_id', $user->id)
                ->where('appointment_id', $reservation_request['appointment_id'])
                ->first();

            if ($existingReservation) {
                return $this->response(false, 400, 'You have already made a reservation for this appointment and doctor');
            } else {
                try {
                    if ($appointment) {
                        $appointment->count++;
                        if ($appointment->max_patients >= $appointment->count) {
                            $appointment->status = 'completed';
                        }
                        $appointment->save();

                        $reservation = Reservation::create($reservation_request);

                        // $doctor->notify(new ReservationNotification($reservation));

                        return $this->response(true, 200, 'ok', $reservation);
                    }
                } catch (\Exception $e) {
                    return $this->response(false, 400, 'Failed to create reservation');
                }
            }
        }
    }// end method

    


}








