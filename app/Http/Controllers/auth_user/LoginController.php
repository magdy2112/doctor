<?php

namespace App\Http\Controllers\auth_user;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use HttpResponse;

    public function userlogin(UserLoginRequest $request)
    {
        $credentials = $request->validated();


        $user_data = User::where('email', '=', $credentials['email'])->first();
        if (  $user_data &&Hash::check($credentials['password'], $user_data->password )) {
            $token = $user_data->createToken('login' . $user_data->id)->plainTextToken;
                return $this->response(true, 200, 'user login Successfully', $token);

            } else {
                return $this->response(false, 400, 'error log in');
             }

    } // end method
}
