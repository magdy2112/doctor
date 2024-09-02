<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Reservation;
use App\Notifications\ReservationNotification;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class ReservationController extends Controller
{
    use HttpResponse;
    public function user_reservation(request $request)
    {
        $user = auth()->user();
        if ($user) {

            $reservation_request =  $request->validate([
                'doctor_id' => 'required|integer|exists:doctors,id',
                'reservation_date' => 'required|date',
                'user_id' => 'required',

            ]);

            $doctor_id = request()->input('doctor_id');

            $reservation_date = Carbon::parse(request()->input('reservation_date'))->format('Y-m-d');

            $reservation_request['doctor_id'] = $doctor_id;

            $reservation_request['user_id'] = auth()->user()->id;

            $reservation_request['reservation_date'] = $reservation_date;

            $user_id = auth()->user()->id;

            $reservation_request['user_id'] = $user_id;

            $available_appointments = Appointment::where('doctor_id', $doctor_id)->get();

            $available_appointments_day = Appointment::where('doctor_id', $doctor_id)->pluck('start_time');

            $available_appointments_days = $available_appointments_day->map(function ($startTime) {
                return Carbon::parse($startTime)->format('l');
            })->toArray();

            $today = Carbon::now();

            $carbon_reservation_date = Carbon::parse($reservation_date);

            $reservation_day = $carbon_reservation_date->format('l');
            // dd(  $available_appointments_days);

            if (in_array($reservation_day, $available_appointments_days) && $carbon_reservation_date > $today) {

                $Reservation_store = Reservation::create($reservation_request);

                $doctor = Doctor::find($doctor_id);

                $doctor->notify(new ReservationNotification($Reservation_store));


            //    // Create a pending notification for the doctor
            //    $doctor_notification = new Notification();
            //    $doctor_notification->notifiable_id = $doctor->id;
            //    $doctor_notification->notifiable_type = get_class($doctor);
            //    $doctor_notification->data = ['message' => 'You have a new reservation from ' . $user->name, 'reservation_id' => $Reservation_store->id];
            //    $doctor_notification->status = 'pending'; // Set status to pending
            //    $doctor_notification->save();

            //    // Create a pending notification for the user
            //    $user_notification = new Notification();
            //    $user_notification->notifiable_id = $user->id;
            //    $user_notification->notifiable_type = get_class($user);
            //    $user_notification->data = ['message' => 'Your reservation is pending confirmation from the doctor.'];
            //    $user_notification->status = 'pending'; // Set status to pending
            //    $user_notification->save();

            //    // Send a request to the doctor to confirm the reservation
            //    $doctor_request = new Request();
            //    $doctor_request->reservation_id = $Reservation_store->id;
            //    $doctor_request->status = 'pending'; // Set status to pending
            //    $doctor_request->save();


                return $this->response(true, 200, 'Booking Successfully', $Reservation_store);
            } else {
                return 'not available';
            }
        }
    }

    public function confirmReservation(request $request, $id) {}
}
