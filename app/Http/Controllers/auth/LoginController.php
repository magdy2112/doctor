<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorLoginRequest;
use App\Http\Requests\UserLoginRequest;
use App\Models\Doctor;
use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use HttpResponse;

    public function userlogin(UserLoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::guard('user')->attempt($credentials)) {
            $user_data = User::where('email', '=', $credentials['email'])->first();
            $token = $user_data->createToken('login' . $user_data->id)->plainTextToken;
            return $this->response(true, 200, 'user login Successfully', $token);

        } else {
            return $this->response(false, 400, 'error log in');
        }
    } // end method

    public function doctorLogin(DoctorLoginRequest $request){

        $credentials = $request->validated();

        if (Auth::guard('doctor')->attempt($credentials)) {
            $user_data = Doctor::where('email', '=', $credentials['email'])->first();
            $token= $user_data->createToken('login' . $user_data->id)->plainTextToken;
            return $this->response(true, 200, 'doctor login Successfully', ['Token' => $token, 'userData' =>   $user_data]);

        } else {
            return $this->response(false, 400, 'error log in');
        }

    }

    public function userlogout(Request $request)
    {
        if (Auth::guard('api')->check()) {
            Auth::guard('api')->logout();
            $request->session()->invalidate();
            return $this->response(true, 200, 'User logged out successfully');
        }
    }

    public function doctorlogout(Request $request)
    {
        if (Auth::guard('doctor')->check()) {
            Auth::guard('doctor')->logout();
            $request->session()->invalidate();
            return $this->response(true, 200, 'doctor logged out successfully');
        }
    }
}
