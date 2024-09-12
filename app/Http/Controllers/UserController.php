<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Reservation;
use App\Models\Specialization;
use App\Models\User;
use App\Traits\HttpResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use HttpResponse;

    public function index()
    {
        $usercity = Auth()->user()->city_id;

        $alldoctors = Doctor::with('specialization')
            ->where('city_id', $usercity)
            ->where('status', 'active')
            ->select('name', 'photo', 'specialization_id')
            ->orderBy('specialization_id')->get();

        $alldoctor = Doctor::with('specialization')
            ->where('city_id', '!=', $usercity)
            ->where('status', 'active')
            ->select('name', 'photo', 'specialization_id')
            ->orderBy('specialization_id')->get();

        if ($alldoctors) {
            $alldoctors->makeHidden('specialization_id');
            $alldoctor->makeHidden('specialization_id');

            return $this->response(true, 200, 'ok', ['doctor in city' => $alldoctors, 'doctor out city' => $alldoctor]);
        } else {
            return $this->response(false, 401, 'Unauthorized');
        }
    }





    public function doctorprofile($id)
    {


        $appointment = Doctor::where('status', 'active')
            ->with(['specialization', 'cities', 'qualification'])

            ->find($id);

        if ($appointment) {
            $appointment->makeHidden(['qualification_id','specialization_id','city_id','email_verified_at','created_at','updated_at','deleted_at','status']);
            return $this->response(true, 200, 'ok', $appointment);
        } else {
            return $this->response(false, 404, 'No dates set');
        }
    }


    public function userprofile($id)
    {
        try {

            $user = User::with('cities')->find($id);
            if ($user->id == auth()->user()->id) {
                $user->makeHidden('city_id');
                // $user->cities->makeHidden(['cities.id', 'cities.created_at', 'cities.updated_at']);
                return $this->response(true, 200, 'ok', $user);
            } else {
                return $this->response(false, 404, 'Not found');
            }
        } catch (Exception $e) {
            return $this->response(false, 401, 'Unauthorized');
        }
    }


    public function find_doctor(request $request)
    {
        $doctors = Doctor::with('specialization')->where('name', 'like', "%{$request->input('name')}%")
            ->where('status', 'active')->select('name', 'address', 'photo', 'phone', 'specialization_id')
            ->get();
        //   dd(  $doctors);
        if ($doctors) {
            $doctors->makeHidden('specialization_id');
            return $this->response(true, 200, 'ok', $doctors);
        } else {
            return $this->response(false, 404, ' doctor Not found');
        }
    }
    public function find_doctor_by_specialty(request $request)
    {
        $doctors = Doctor::with('specialization')
            ->where('specialization_id', $request->input('specialization_id'))
            ->where('status', 'active')->select('name', 'photo', 'phone', 'specialization_id')->get();

        if ($doctors) {
            $doctors->makeHidden('specialization_id');

            return $this->response(true, 200, 'ok', $doctors);
        } else {
            return $this->response(false, 404, 'doctor Not found');
        }
    }





    public function doctor_category()
    {
        $categoriess = Specialization::whereHas('doctors', function ($query) {
            $query->where('status', 'active');
        })->with(['doctors' => function ($query) {
            $query->select(['id', 'name', 'photo',   'specialization_id']);
        }])->get();

        return $this->response(true, 200, 'ok', $categoriess);
    }
    public function all_appointments($id){
        $doctor=Doctor::find($id);
        $appointments = $doctor->appointments->where('status','active');

        $appointments->makeHidden('doctor_id');
        return $this->response(true,200,'ok',$appointments);
    }

    public function user_reservation(Request $request){
        $user = auth()->user();
        if ($user) {

            $reservation_request =  $request->validate([
                'doctor_id' =>'required|integer|exists:doctors,id',
                'appointment_id' =>'required|exists:appointments,id',
                'user_id' =>'',

            ]);


            $reservation_request['doctor_id'] = request()->input('doctor_id');

            $reservation_request['user_id'] = auth()->user()->id;

            $reservation_request['appointment_id'] =request()->input('appointment_id');
            
            $all_doctor_appoinments = Appointment::where('doctor_id',$reservation_request['doctor_id'])->get();
            foreach ($all_doctor_appoinments as $app) {
                if($app->appointment_id == $reservation_request['appointment_id']){
                    return $this->response(false,400,'You already have an appointment with this doctor on this date');
                }else{
                       Reservation::create($reservation_request);

                }
            }




    }
}
}
