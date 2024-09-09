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

        $alldoctors = Doctor::where('city_id', $usercity)->where('status', 'active')->select('name', 'photo', 'specialization_id')->orderBy('specialization_id')->paginate(10);
        $alldoctor = Doctor::where('city_id', '!=', $usercity)->where('status', 'active')->select('name', 'photo', 'specialization_id')->orderBy('specialization_id')->paginate(10);

        if ($alldoctors) {

            return $this->response(true, 200, 'ok', ['doctor in city' => $alldoctors, 'doctor out city' => $alldoctor]);
        } else {
            return $this->response(false, 401, 'Unauthorized');
        }
    }

    public function doctorprofile($id)
    {


        $appointment = Doctor::where('status', 'active')->with(['appointments' => function ($query) {
            $query->select('id', 'doctor_id', 'start_time', 'end_time', 'status', 'date');
        }])->find($id);

        if ($appointment) {
            return $this->response(true, 200, 'ok', $appointment);
        } else {
            return $this->response(false, 404, 'No dates set');
        }
    }



    public function bookappointment(request $request) {}


    public function userprofile($id)
    {
        try {

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
    public function find_doctor(request $request)
    {
        $doctors = Doctor::where('name', 'like', "%{$request->input('name')}%")->where('status', 'active')->paginate(10);
        //   dd(  $doctors);
        if ($doctors) {
            return $this->response(true, 200, 'ok', $doctors);
        } else {
            return $this->response(false, 404, ' doctor Not found');
        }
    }
    public function find_doctor_by_specialty(request $request)
    {

        $doctors = Doctor::where('specialization_id', $request->input('specialization_id'))->where('status', 'active')->paginate(10);
        if ($doctors) {
            return $this->response(true, 200, 'ok', $doctors);
        } else {
            return $this->response(false, 404, 'doctor Not found');
        }
        // if ($doctors) {
        //     $data = $doctors->map(function ($doctor) {
        //        $Specialization_name = $doctor->specialization_id ;
        //        $specialization = Specialization::find($Specialization_name);
        //        return [

        //         'doctor' => $doctor,
        //         'specialization' => $specialization,
        //     ];

        //     });

        //     return $this->response(true, 200, 'ok', $data);
        // } else {
        //     return $this->response(false, 404, 'doctor Not found');
        // }
    }

    public function doctor_category()
    {


        $categoriess = Specialization::whereHas('doctors', function ($query) {
            $query->where('status', 'active');
        })->with('doctors')->paginate(10);
        return $this->response(true, 200, 'ok', $categoriess);
    }
}
