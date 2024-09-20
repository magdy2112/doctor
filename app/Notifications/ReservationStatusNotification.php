<?php

namespace App\Notifications;

use App\Models\Appointment;
use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class ReservationStatusNotification  extends Notification implements ShouldQueue{


    use Queueable;
    public function __construct(public Appointment $appointment, public Collection $reservationIds)
    {
        $this->appointment = $appointment;
        $this->reservationIds = $reservationIds;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */ public function toDatabase(object $notifiable): array
    {
        $notifications = [];

        foreach ($this->reservationIds as $userId) {
            $notifications[] = [
                'subject' => 'The reservation date has been changed.',
                'message' => 'Dr. ' . $this->appointment->doctor->name .  'changed the appointment date.',
                'reservation_date' => $this->appointment->date,
                'user_id' => $userId,
            ];
        }

        return $notifications;
    }


        /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }









}



