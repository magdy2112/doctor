<?php

namespace App\Http\Controllers\auth_doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocterRegisterRequest;
use App\Models\Doctor;
use App\Traits\HttpResponse;
use Illuminate\Support\Facades\Hash;

class RegisterDoctorController extends Controller
{
    use HttpResponse;

    public function doctorregister(DocterRegisterRequest $request)
    {

        $validatedData = $request->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);
        $user =  Doctor::create($validatedData);
        if ($user) {
            if ($request->file('photo')) {
                $file = $request->file('photo');
                // @unlink remove old image
                @unlink(public_path('upload/doctor_images' . $user->photo));
                $file_name = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('upload/doctor_images'), $file_name);
                $user['photo'] = $file_name;
                // $user->save();
            }


            return $this->response(true, 200, 'register Successfully', $user);
        } else {
            return $this->response(false, 400, 'Failed to register');
        }
    }
}
