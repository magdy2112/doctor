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



    public function doctor_cancel_appointment(Request $request)
    {
        try {
            $id = $request->input('id');
            $appointment = Appointment::find($id);

            if (!$appointment) {
                return $this->response(false, 404, 'Appointment not found');
            }

            if ($appointment->doctor_id !== Auth::guard('doctor')->user()->id) {
                return $this->response(false, 403, 'You are not authorized to cancel this appointment');
            }

            $reservations = Reservation::where('appointment_id', $id)->get();

            if ($reservations->isEmpty()) {
                return $this->response(false, 404, 'Reservations not found');
            }

            Reservation::where('appointment_id', $id)->update(['status' => 'cancelled']);

            $appointment->status = 'cancelled';
            $appointment->save();

            return $this->response(true, 200, 'Appointment cancelled successfully', $appointment);
        } catch (\Exception $e) {
            return $this->response(false, 500, 'something wrong ', );
        }
    }

    public function doctor_update_Appoinment(Request $request){
        $today = Carbon::today();
       $update_request= $request->validate([
            'date' => 'date|after_or_equal:' . $today->format('Y-m-d'),
           'starttime' =>'date_format:H:i',
           'endtime' =>'date_format:H:i',
           'max_patients' =>'integer',
           'id'=>'exists:appointments,id',
        ]);
        $update_request['id'] =  request()->input('id');
        $appointment = Appointment::find(  $update_request['id']);
        if ($appointment && $appointment->doctor_id == Auth::guard('doctor')->user()->id) {
            $appointment->update([
                'date' => request()->input('date'),
               'start_time' => request()->input('starttime'),
                'end_time' => request()->input('endtime'),
               'max_patients' => request()->input('max_patients'),
            ]);
            return $this->response(true, 200,  'Appointments updated successfully',$appointment);
        } else {
            return $this->response(false, 404, 'Appointment not found or you are not authorized to updated it');
        }
    }


}
