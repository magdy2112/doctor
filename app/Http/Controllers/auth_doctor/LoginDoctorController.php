<?php

namespace App\Http\Controllers\auth_doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorLoginRequest;
use App\Models\Doctor;
use App\Traits\HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginDoctorController extends Controller
{
    use HttpResponse;

// end method

    public function doctorLogin(DoctorLoginRequest $request){

        $credentials = $request->validated();

           $doctor_data = Doctor::where('email', '=', $credentials['email'])->first();

        if (   $doctor_data && Hash::check($credentials['password'], $doctor_data->password)) {
            $token =   $doctor_data->createToken('login' .    $doctor_data->id)->plainTextToken;
                return $this->response(true, 200, 'user login Successfully', $token);

            } else {
                return $this->response(false, 400, 'error log in');
             }

    }




}
