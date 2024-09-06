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

        $usercity = Auth()->user()->city;

        $alldoctors = Doctor::where('city', $usercity)->select('name','photo','specialization')->orderBy('specialization')->paginate(10);
        $alldoctor = Doctor::clone()->where('city', '!=', $usercity)->select('name','photo','specialization')->orderBy('specialization')->paginate(10);

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
    public function find_doctor(request $request){
      $doctors = Doctor::where('name','like',"%{$request->input('name')}%")->get();
    //   dd(  $doctors);
     if ( $doctors) {
       return $this->response(true,200,'ok',$doctors);
     }else{
        return $this->response(false,404,'Not found');
     }

    }
    public function find_doctor_by_specialty(request $request){
        $doctors = Doctor::where('specialization','like',"%{$request->input('specialty')}%")->get();
     if ( $doctors) {
        return $this->response(true,200,'ok',$doctors);
    }else{
       return $this->response(false,404,'Not found');
    }
}
}
