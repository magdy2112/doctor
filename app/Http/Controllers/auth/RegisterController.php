<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocterRegisterRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Doctor;
use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use function Laravel\Prompts\password;

class RegisterController extends Controller
{
    use HttpResponse;
    public function userregister(RegisterRequest $request)
    {

        $validatedData = $request->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);
        $user =  User::create($validatedData);


        if ($user) {
            if ($request->file('photo')) {
                $file = $request->file('photo');

                // @unlink remove old image
                @unlink(public_path('upload/user_images' . $user->photo));
                $file_name = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('upload/user_images'), $file_name);
                $user['photo'] = $file_name;
            }

            return $this->response(true, 200, 'register Successfully', $user);
        } else {
            return $this->response(false, 400, 'Failed to register');
        }
    }

    public function doctorregister(DocterRegisterRequest $request)
    {

        $validatedData = $request->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);
        $user =  Doctor::create($validatedData);


        if ($user) {
            if ($request->file('photo')) {
                $file = $request->file('photo');

                // @unlink remove old image
                @unlink(public_path('upload/user_images' . $user->photo));
                $file_name = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('upload/user_images'), $file_name);
                $user['photo'] = $file_name;
            }

            return $this->response(true, 200, 'register Successfully', $user);
        } else {
            return $this->response(false, 400, 'Failed to register');
        }
    }
}
