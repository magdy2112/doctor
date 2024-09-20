<?php

namespace App\Http\Controllers;
use App\Models\Doctor;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
use HttpResponse;

    public function doctorprofile(){

     $id = Auth::guard('doctor')->user()->id;
    $doctor = Doctor::where('id',$id)
    ->with('specialization','cities','qualification')->get();
       if ( $doctor) {
        $doctor->makeHidden(['qualification_id','specialization_id','city_id',]);
        return   $this->response(true,200, 'ok',$doctor);
       }else{
        return   $this->response(false,404,'doctor Not found');
       }
      }

      public function update_DoctorPassword(Request $request){

        $doctor = Doctor::find(Auth::guard('doctor')->user()->id);

        if( $doctor){
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|confirmed',
            ]);
            if(Hash::check($request->input('current_password'),  $doctor->password)){

                   $doctor->update([
                    'password' => Hash::make($request->input('new_password')),
                ]);
                return $this->response(true, 200, 'Password updated successfully');
            }else{
                return $this->response(false, 400, 'Current password is incorrect');
            }
        }else{
            return $this->response(false, 401, 'Unauthorized');
        }
    }

  public function update_doctor_profile(Request $request){
        $doctor_id= Doctor::find(Auth::guard('doctor')->user()->id);
        $doctor=Doctor::find(Auth::guard('doctor')->user());
        if ( $doctor_id) {
           $doctordata= $request->validate([
                'name' =>'string',
                'email' => 'email|unique:doctors,email,',
                'phone' =>'string|unique:doctors,phone,',
                'address' =>'string',
                'experience' =>'string',
                'qualification_id' =>'integer',
                'specialization_id' =>'integer',
                'city_id' =>'integer',
                'photo'=>'mimes:jpg,jpeg',
            ]);

               if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                // @unlink remove old image
                @unlink(public_path('upload/doctor_images' .   $doctor->photo));
                $file_name = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('upload/doctor_images'), $file_name);
                $doctor['photo'] = $file_name;
               }

            $doctor->update($doctordata);
            return $this->response(true, 200, 'Profile updated successfully');
        }else{
            return $this->response(false, 404, 'doctor Not found');
        }


  }
}
