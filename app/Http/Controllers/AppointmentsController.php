<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

use App\Models\Doctor;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\ReservationStatusNotification;
use App\Traits\HttpResponse;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AppointmentsController extends Controller
{
    use HttpResponse;
    public function doctor_set_appoinments(Request $request)
    {
        $appointment = new Appointment();
        $appointment->doctor_id = Auth::guard('doctor')->user()->id;
        $appointment->date = date('Y-m-d', strtotime($request->input('date')));
        $appointment->start_time = $request->input('starttime');
        $appointment->end_time = $request->input('endtime');
        $appointment->max_patients = $request->input('max_patients');

        $validationRules = [
            'starttime' => 'required|date_format:H:i',
            'endtime' => 'required|date_format:H:i',
            'max_patients' => 'integer',
            'date' => 'required',
            'doctor_id' => 'exists:doctors,id',
            'starttime' => Rule::unique('appointments', 'start_time')
                ->where(function ($query) use ($request, $appointment) {
                    $start_time = $request->input('starttime');
                    if (!empty($start_time)) {
                        $query->where('doctor_id', $appointment->doctor_id)
                            ->where('date', $appointment->date)
                            ->where('start_time', $start_time);
                    }
                }),
            'endtime' => Rule::unique('appointments', 'end_time')
                ->where(function ($query) use ($request, $appointment) {
                    $end_time = $request->input('endtime');
                    if (!empty($end_time)) {
                        $query->where('doctor_id', $appointment->doctor_id)
                            ->where('date', $appointment->date)
                            ->where('end_time', $end_time);
                    }
                }),
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails() || $request->input('date') >= Carbon::today()->addDays(30)->format('Y-m-d')) {
            $errors = $validator->errors();
            $errorMessages = [];
            foreach ($errors->all() as $error) {
                $errorMessages[] = $error;
            }
            return $this->response(false, 422, 'Validation errors', $errorMessages);
        } else {
            try {
                $appointment->save();
                return $this->response(true, 200, 'ok', 'Appointments created');
            } catch (\Exception $e) {
                return $this->response(false, 400, 'Bad Request', $e->getMessage());
            }
        }
    }


    public function doctor_cancel_appointment(Request $request){
        $id = $request->input('id');
        $appointment = Appointment::find($id);
        $reservation = Reservation::where('appointment_id',$id)->first();
        if ($appointment && $appointment->doctor_id == Auth::guard('doctor')->user()->id) {
             $reservation->status='cancelled';
            $appointment->status = 'cancelled';
            $appointment->save();
            $reservation->save();


            return $this->response(true, 200,  'Appointment canceled successfully',$appointment);
        } else {
            return $this->response(false, 404, 'Appointment not found or you are not authorized to updated it');
        }
    }


}
