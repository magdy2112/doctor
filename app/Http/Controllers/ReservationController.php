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
}
  public function get_special_doctor_reservation(Request $request){
    $request->validate([
       'appointment_id' =>'required|exists:specializations,id'
    ]);
    $id= Auth::guard('doctor')->user()->id;
   $allreservations= Reservation::where([
     'doctor_id'=>$id,
     'appointment_id'=>$request->input('appointment_id')
   ]);
  $All_reservation_related_appointmen= $allreservations::with('appointment')->get();
  if (  $All_reservation_related_appointmen) {
  $allreservations->makeHidden(['user_id','doctor_id','appointment_id','id','created_at','updated_at']);
  return $this->response(true, 200, 'ok',      ['all_reservation_related_appointments'=> $All_reservation_related_appointmen]);
  }else{
    return $this->response(false, 404, 'No reservations found');
  }

  }

  public function get_all_user_reservation(){
    try {
        $id = Auth::guard('user')->user()->id;
        $allreservations = Reservation::where([
            'user_id' => $id,
            'status' => 'confirmed'
        ])->with('appointment')->get();

        if ($allreservations) {
            return $this->response(true, 200, 'ok', ['all_reservation' => $allreservations]);
        } else {
            return $this->response(false, 404, 'No reservations found');
        }
    } catch (\Exception ) {
        return $this->response(false, 500, 'Unauthorized');
}

}

public function user_cancel_reservation(Request $request){
    $request->validate([
        'reservation_id' =>'required|exists:reservations,id'
    ]);


    $id= Auth::guard('user')->user()->id;
    $reservation = Reservation::where([
        'user_id' => $id,
        'reservation_id' => $request->input('reservation_id'),
       'status' => 'confirmed'
    ])->first();

    $appointment = Appointment::where([
        'id' => $reservation->appointment_id,
        'status' <> 'cancelled',

    ])->first();
    if ($reservation->exists()) {
        $reservation->delete();
        $appointment->count--;
       $appointment->save;
        return $this->response(true, 200, 'Appointment canceled successfully', $reservation);
    } else {
        return $this->response(false, 404, 'Appointment not found or you are not authorized to updated it');
    }
}


public function user_reservation(Request $request)
    {

        $user = user::find(Auth::guard('user')->user()->id) ;
        if ($user) {

            $reservation_request =  $request->validate([

                'doctor_id' => 'required|integer|exists:doctors,id',
                'appointment_id' => 'required|exists:appointments,id,doctor_id,' . $request->input('doctor_id') . ',status,active',
                'user_id' => '',

            ]);
            //             required: This means that the appointment_id field is required in the request. If it's not present, the validation will fail.
            // exists: This rule checks if the value of the appointment_id field exists in a specific table and column.
            // appointments: This is the name of the table to check.
            // id: This is the column name in the appointments table to check against the value of the appointment_id field.
            // doctor_id: This is an additional column in the appointments table to filter the results. We want to check if the appointment_id exists in the appointments table, but only for the specific doctor_id that was sent in the request.
            // $request->input('doctor_id'): This is the value of the doctor_id field sent in the request. We're using this value to filter the results in the appointments table.


            $reservation_request['doctor_id'] = request()->input('doctor_id');

            $reservation_request['user_id'] =$user->id;

            $reservation_request['appointment_id'] = request()->input('appointment_id');
            $doctor =Doctor::find( $reservation_request['doctor_id'])->where('status','active')->first();

            $appointment = Appointment::where('id', request()->input('appointment_id'))->first();
            $maxCount = $appointment->max_patients;



            $exist = Reservation::where('doctor_id', request()->input('doctor_id'))
                ->where('user_id', $user->id)
                ->where('appointment_id', request()->input('appointment_id'))->count();
            if ($exist) {
                return $this->response(false, 400, 'Reservation Failed');
            } else {
                try {
                    if ($appointment) {
                        $appointment->count++;
                        if ($appointment->max_patients >=  $appointment->count) {
                            $appointment->status = 'completed';
                        }
                        $appointment->save();


                        $reservation =  Reservation::create($reservation_request);

                        // $doctor->notify(new ReservationNotification($reservation));

                        return $this->response(true, 200, 'ok', $reservation);

                    }
                } catch (\Exception $e) {
                    return $this->response(false, 400, 'Failed to create reservation');
                }
            }
        }
    }


}








