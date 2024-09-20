<?php

namespace App\Http\Controllers;

use App\Mail\Resetpassword;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\PasswordResetToken;
use App\Models\Reservation;
use App\Models\Specialization;
use App\Models\User;
use App\Notifications\ReservationNotification;
use App\Traits\HttpResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyPasswordReset;




class UserController extends Controller
{
    use HttpResponse;

    public function user_home()
    {

        $usercity = Auth::guard('user')->user()->city;

        $alldoctors = Doctor::with('specialization')
            ->where('city_id', $usercity)
            ->where('status', 'active')
            ->select('name', 'photo', 'specialization_id')
            ->orderBy('specialization_id')->get();

        $alldoctor = Doctor::with('specialization')
            ->where('city_id', '!=', $usercity)
            ->where('status', 'active')
            ->select('name', 'photo', 'specialization_id')
            ->orderBy('specialization_id')->get();

        if ($alldoctors) {

            $alldoctors->makeHidden('specialization_id');

            $alldoctor->makeHidden('specialization_id');

            return $this->response(true, 200, 'ok', ['doctor in city' => $alldoctors, 'doctor out city' => $alldoctor]);

        } else {

            return $this->response(false, 401, 'Unauthorized');
        }
    }
/******************************************************************************************************************************************************************************************************************************* */
    public function doctorprofile(Request $request)
    {
         $request->validate([
            'doctor_id' =>'required|exists:doctors,id'
        ]);

        $appointment = Doctor::where('status', 'active')
        ->with(['specialization', 'cities', 'qualification'])
         ->find(request()->input('doctor_id'));

        if ($appointment) {
            // Hide unnecessary attributes from the response
            $appointment->makeHidden(['qualification_id', 'specialization_id', 'city_id', 'email_verified_at', 'created_at', 'updated_at', 'deleted_at', 'status']);

            return $this->response(true, 200, 'ok', $appointment);
        } else {
            return $this->response(false, 404, 'No dates set');
        }
    }

/******************************************************************************************************************************************************************************************************************************* */

    public function userprofile()
    {
        $id= Auth::guard('user')->user()->id;
        try {
            $user = User::with('cities')->find($id);

            if ($user->id == Auth::guard('user')->user()->id ) {

                $user->makeHidden('city_id');

                return $this->response(true, 200, 'ok', $user);

            } else {

                return $this->response(false, 404, 'Not found');
            }

        } catch (Exception $e) {

            return $this->response(false, 401, 'Unauthorized');
        }
    }

/******************************************************************************************************************************************************************************************************************************* */

    public function find_doctor_name(request $request)
    {

        $request->validate([
            'name' =>'required|string'
        ]);
        $doctors = Doctor::with('specialization')->where('name', 'like', "%{$request->input('name')}%")

            ->where('status', 'active')->select('name', 'address', 'photo', 'phone', 'specialization_id')->get();


        if ($doctors) {

            $doctors->makeHidden('specialization_id');

            return $this->response(true, 200, 'ok', $doctors);

        } else {

            return $this->response(false, 404, ' doctor Not found');
        }
    }
    /******************************************************************************************************************************************************************************************************************************* */
    public function find_doctor_by_specialty(request $request)
    {

        $request->validate([
           'specialization_id' =>'required|exists:specializations,id'
        ]);
        $doctors = Doctor::with('specialization')

            ->where('specialization_id', $request->input('specialization_id'))

            ->where('status', 'active')->select('name', 'photo', 'phone', 'specialization_id')->get();

        if ($doctors) {

            $doctors->makeHidden('specialization_id');

            return $this->response(true, 200, 'ok', $doctors);

        } else {

            return $this->response(false, 404, 'doctor Not found');
        }
    }



/******************************************************************************************************************************************************************************************************************************* */


    public function doctor_category()
    {
        $categoriess = Specialization::whereHas('doctors', function ($query) {

            $query->where('status', 'active');

        })->with(['doctors' => function ($query) {

            $query->select(['id', 'name', 'photo',   'specialization_id']);

        }])->get();

        return $this->response(true, 200, 'ok', $categoriess);
    }


        // Specialization::whereHas('doctors', function ($query) { ... }):
        // whereHas is a method that allows us to filter the specializations based on a condition in the related doctors table.
        // The closure function passed to whereHas defines the condition: where('status', 'active'). This means we only want to consider specializations that have at least one doctor with an active status.
        // ->with(['doctors' => function ($query) { ... }]):
        // with is a method that allows us to eager-load the related doctors table.
        // The closure function passed to with defines the columns we want to select from the doctors table: select(['id', 'name', 'photo', 'specialization_id']). This means we only want to retrieve the id, name, photo, and specialization_id columns from the doctors table.
        // ->get():
        // Finally, we call the get() method to execute the query and retrieve the results.
        // The resulting $categoriess variable will contain a collection of specializations, each with a nested collection of associated doctors that have an active status. The doctors will only have the selected columns (id, name, photo, and specialization_id).



/******************************************************************************************************************************************************************************************************************************* */
public function updateprofile(Request $request){
    $user = user::find( Auth::guard('user')->user()->id);
    if( $user){
       $userdata= $request->validate([
            'name' =>'string',
            'email' =>'email|unique:users,email,',
            'phone' =>'string|min:11|unique:users,phone,',
            'city_id' =>'exists:cities,id',
            'address' =>'string',
            'gender' =>'string',
            'photo'=>'mimes:jpg,jpeg'
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            // @unlink remove old image
            @unlink(public_path('upload/user_images' .    $user->photo));
            $file_name = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/user_images'), $file_name);
            $user['photo'] = $file_name;
           }
           $user->update($userdata);
           return $this->response(true, 200, 'User updated successfully', $user);

    }else{
        return $this->response(false, 401, 'Unauthorized');
    }

}
}
