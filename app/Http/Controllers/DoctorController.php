<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\ReservationStatusNotification;
use App\Traits\HttpResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
use HttpResponse;

    public function doctorprofile($guards='doctor'){
        $id=auth()->user()->id;

    //    $doctor_reservations=  Doctor::with('reservations')->where('id',operator: $id)->paginate(10);
    //    $doctor_reservations=  Doctor::with('reservations')->where('id',operator: $id)->paginate(10);
    $doctor = Doctor::where('id',$id)
    ->with('specialization','cities','qualification')->get();
       if (     $doctor) {
        $doctor->makeHidden(['qualification_id','specialization_id','city_id',]);

        return   $this->response(true,200, 'ok',$doctor);
       }else{
        return   $this->response(false,404,'doctor Not found');
       }



// dd($id);


      }


public function set_appoinments(Request $request)
{
    $appointment = new Appointment();
    $appointment->date = date('Y-m-d', strtotime($request->input('date')));
    $appointment->start_time = $request->input('starttime');
    $appointment->end_time = $request->input('endtime');
    $appointment->max_patients = $request->input('max_patients');
    $Rules = [

        'starttime' => Rule::unique('appointments', 'start_time')->where(function ($query) use ($request) {
            $query->where('doctor_id', Auth::id())
                ->where('date', $request->input('date'))
                ->where('start_time', $request->input('starttime'));
        }),
        'endtime' => Rule::unique('appointments', 'end_time')->where(function ($query) use ($request) {
            $query->where('doctor_id', Auth::id())
                ->where('date', $request->input('date'))
                ->where('end_time', $request->input('endtime'));
        }),
        'max_patients'=>'required|integer',
        'date' => 'required',

    ];



    $validator = Validator::make($request->all(),   $Rules);
    $today = Carbon::today()->addDays(30)->format('Y-m-d');
    try{
        if ($validator->fails()||$request->input('date')>= $today) {
            return $this->response(false, 422, 'Validation errors', );
        } else {
            $appointment->fill($request->all());
            $appointment->doctor_id = Auth()->id();
            $appointment->save();

            return $this->response(true, 200, 'Appointment created successfully', $appointment);
        }
    }catch (\Exception ){
                  return $this->response(true, 400, ' Bad Request');

    }


}
public function cancel_appointment($id){
    $appointment = Appointment::find($id);
    $reservation = Reservation::where('appointment_id',$id)->first();
    if ($appointment && $appointment->doctor_id == Auth::id()) {
         $reservation->status='cancelled';
        $appointment->status = 'cancelled';
        $appointment->save();
        $reservation->save();


        return $this->response(true, 200,  'Appointment canceled successfully',$appointment);
    } else {
        return $this->response(false, 404, 'Appointment not found or you are not authorized to updated it');
    }
}
public function get_reservations(){

      $id= auth()->user()->id;
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

public function doctorlogout(Request $request)
{
    $doctor =auth()->user();
    if ($doctor) {
        $request->user()->currentAccessToken()->delete();
        return $this->response(true, 200, 'doctor logged out successfully');
    }else{
        return $this->response(false, 401, 'doctor not logged in');
    }

}

public function update_password(Request $request){
    // dd($request->all());
    $user = auth()->user();
    if($user){
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);
        if(Hash::check($request->input('current_password'), $user->password)){
            $user->update([
                'password' => Hash::make($request->input('new_password')),
            ]);
            return $this->response(true, 200, 'Password updated successfully');
        }else{
            return $this->response(false, 400, 'Current password is incorrect');
        }
    }else{
        return $this->response(false, 401, 'Unauthorized');
    }
}



}
