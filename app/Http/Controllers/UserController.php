<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Reservation;
use App\Models\User;
use App\Traits\HttpResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use HttpResponse;
    public function index()
    {
        //show all doctor

        // $usercity = Auth()->guard('api')->city
        $usercity = Auth()->user()->city;
        //  dd($usercity);
        //   $usercity = auth('api')->user()->city;
        $alldoctors = Doctor::where('city', $usercity)->orderBy('name')->get();
        $alldoctor = Doctor::clone()->where('city', '!=', $usercity)->orderBy('name')->get();



        if ($alldoctors) {

            return $this->response(true, 200, 'ok', ['doctor in city' => $alldoctors, 'doctor out city' => $alldoctor]);
        } else {
            return $this->response(false, 401, 'Unauthorized');
        }
    }

    public function doctorprofile($id)
    {


        $appointment = Doctor::with(['appointments' => function ($query) {
            $query->select('id', 'doctor_id', 'start_time', 'end_time', 'status', 'notes');
        }])->find($id);

        if ($appointment) {
            return $this->response(true, 200, 'ok', $appointment);
        } else {
            return $this->response(false, 404, 'Not found');
        }
    }


    // public function reservation(request $request)
    // {
    //     $user = auth()->user();
    //     if ($user) {

    //         $reservation_request =  $request->validate([
    //             'doctor_id' => 'required|integer|exists:doctors,id',
    //             'reservation_date' => 'required|date',
    //             'user_id'=>'required',

    //         ]);
    //         $doctor_id = request()->input('doctor_id');

    //         $reservation_date = Carbon::parse(request()->input('reservation_date'))->format('Y-m-d');



    //         $reservation_request['doctor_id'] = $doctor_id;
    //         $reservation_request['user_id'] =auth()->user()->id;


    //         $reservation_request['reservation_date'] = $reservation_date;



    //         $user_id = auth()->user()->id;

    //         $reservation_request['user_id'] = $user_id;

    //         $available_appointments = Appointment::where('doctor_id', $doctor_id)->get();

    //         $available_appointments_day = Appointment::where('doctor_id', $doctor_id)->pluck('start_time');

    //         $available_appointments_days = $available_appointments_day->map(function ($startTime) {
    //             return Carbon::parse($startTime)->format('l');
    //         })->toArray();

    //         $today = Carbon::now();

    //         $carbon_reservation_date = Carbon::parse($reservation_date);

    //         $reservation_day = $carbon_reservation_date->format('l');


    //         if (in_array($reservation_day, $available_appointments_days) && $carbon_reservation_date > $today) {
    //             $Reservation_store = Reservation::create($reservation_request);
    //             return $this->response(true,200,'Booking Successfully', $Reservation_store);
    //         } else {
    //             return 'not available';
    //         }




    //         // return  $this->response(true, 200, 'Available appointments', ['user_id' => $user_id, 'available_appointments' => $available_appointments]);
    //     }
    // }

    public function bookappointment(request $request) {}


    public function userprofile($id)
    {
        try {
            // $id = auth()->user()->id;
            $user = User::find($id);
            if ($user->id == auth()->user()->id) {
                return $this->response(true, 200, 'ok', $user);
            } else {
                return $this->response(false, 404, 'Not found');
            }
        } catch (Exception $e) {
            return $this->response(false, 401, 'Unauthorized');
        }
    }


    // public function userprofile(){
    //     $user = auth()->user();
    //     if ($user) {
    //         return $this->response(true, 200, 'ok', $user);
    //     } else {
    //         return $this->response(false, 401, 'Unauthorized');
    //     }
    // }


}
