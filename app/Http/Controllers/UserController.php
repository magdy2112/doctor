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









































    // public function user_reservation(Request $request)
    // {

    //     $user = auth()->user();
    //     if ($user) {

    //         $reservation_request =  $request->validate([

    //             'doctor_id' => 'required|integer|exists:doctors,id',
    //             'appointment_id' => 'required|exists:appointments,id,doctor_id,' . $request->input('doctor_id') . ',status,active',
    //             'user_id' => '',

    //         ]);
    //         //             required: This means that the appointment_id field is required in the request. If it's not present, the validation will fail.
    //         // exists: This rule checks if the value of the appointment_id field exists in a specific table and column.
    //         // appointments: This is the name of the table to check.
    //         // id: This is the column name in the appointments table to check against the value of the appointment_id field.
    //         // doctor_id: This is an additional column in the appointments table to filter the results. We want to check if the appointment_id exists in the appointments table, but only for the specific doctor_id that was sent in the request.
    //         // $request->input('doctor_id'): This is the value of the doctor_id field sent in the request. We're using this value to filter the results in the appointments table.


    //         $reservation_request['doctor_id'] = request()->input('doctor_id');

    //         $reservation_request['user_id'] = auth()->user()->id;

    //         $reservation_request['appointment_id'] = request()->input('appointment_id');
    //         $doctor =Doctor::find( $reservation_request['doctor_id'])->where('status','active')->first();

    //         $appointment = Appointment::where('id', request()->input('appointment_id'))->first();
    //         $maxCount = $appointment->max_patients;



    //         $exist = Reservation::where('doctor_id', request()->input('doctor_id'))
    //             ->where('user_id', auth()->user()->id)
    //             ->where('appointment_id', request()->input('appointment_id'))->count();
    //         if ($exist) {
    //             return $this->response(false, 400, 'Reservation Failed');
    //         } else {
    //             try {
    //                 if ($appointment) {
    //                     $appointment->count++;
    //                     if ($appointment->max_patients >=  $appointment->count) {
    //                         $appointment->status = 'completed';
    //                     }
    //                     $appointment->save();


    //                     $reservation =  Reservation::create($reservation_request);

    //                     // $doctor->notify(new ReservationNotification($reservation));

    //                     return $this->response(true, 200, 'ok', $reservation);

    //                 }
    //             } catch (\Exception $e) {
    //                 return $this->response(false, 400, 'Failed to create reservation');
    //             }
    //         }
    //     }
    // }

/******************************************************************************************************************************************************************************************************************************* */

    // public function Available_appointments()
    // {
    //     $doctor = Doctor::find(request()->input('doctor_id'));
    //     $appointments = $doctor->appointments->where('status', 'active');
    //     //doctor hasmany appoinments

    //     $appointments->makeHidden('doctor_id');
    //     return $this->response(true, 200, 'ok', $appointments);
    // }
/******************************************************************************************************************************************************************************************************************************* */

// public function my_reservations(){
//     $user = auth()->user()->id;
    // if($user){
    //     $reservations = Reservation::where('user_id',   $user)->with(['doctor' => function($query){
    //         $query->select(['id', 'name', 'photo']);
    //     }, 'appointment' => function($query){
    //         $query->select(['id', 'date', 'time']);
    //     }])->get();

    //     return $this->response(true, 200, 'ok', $reservations);
    // }else{
    //     return $this->response(false, 401, 'Unauthorized');
    // }
//     if ( $user) {
//        $reservation =Reservation::where('user_id',$user)

//     }
// }
/******************************************************************************************************************************************************************************************************************************* */


/******************************************************************************************************************************************************************************************************************************* */




/******************************************************************************************************************************************************************************************************************************* */




}
