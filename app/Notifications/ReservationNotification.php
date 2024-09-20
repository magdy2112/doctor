<?php

namespace App\Notifications;

use App\Models\Appointment;
use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;



class ReservationNotification extends Notification implements ShouldQueue

{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct( public $reservationIds,public $appointment)
    {

        $this->reservationIds = $reservationIds;
        $this->appointment = $appointment;
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
     * Get the mail representation of the notification*.*/
public function toDatabase(object $notifiable): array
{
    $notificationData = [
        'subject' => 'your reservation cancelled',
        'message' => 'Dr. apologizes for the examination appointment. We hope that you will book another appointment',
        // 'reservation_date' => $this->appointment->date,
    ];



    return $notificationData;
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




