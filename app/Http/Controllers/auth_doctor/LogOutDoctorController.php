<?php

namespace App\Http\Controllers\auth_doctor;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;



class LogOutDoctorController extends Controller
{
    use HttpResponse;
    public function doctorlogout(Request $request)
    {


        $user=Auth::guard('doctor')->user();
        if (   $user) {
            # code...
            $request->user()->currentAccessToken()->delete();
            return $this->response(true,200,'Doctor log out');
        }else{
            return $this->response(false,400,'error log out');
        }



    }
}
