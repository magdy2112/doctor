<?php

namespace App\Http\Controllers\auth_user;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    use HttpResponse;
    public function user_register(RegisterRequest $request)
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
}
