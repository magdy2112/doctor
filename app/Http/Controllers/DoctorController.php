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

class DoctorController extends Controller
{
use HttpResponse;

    public function doctorprofile(){
        $id=auth()->user()->id;
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

public function set_appoinments(Request $request)
{
    $appointment = new Appointment();
    $appointment->date = date('Y-m-d', strtotime($request->input('date')));
    $appointment->start_time = $request->input('starttime');
    $appointment->end_time = $request->input('endtime');
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


}}
