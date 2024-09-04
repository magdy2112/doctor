<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;



class ReservationStatusNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Reservation $reservation)
    {
        $this->reservation = $reservation;
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
     */
    public function toDatabase(object $notifiable): array
    {
        $status = $this->reservation->status;
        $message = '';

        if ($status == 'confirmed') {
            $message = 'Your reservation has been confirmed by the doctor.';
        } elseif ($status == 'cancelled') {
            $message = 'Your reservation has been cancelled by the doctor.';
        }else{
            $message = 'Your reservation is pending ' ;
        }

        return [
            'message' => $message,
            'reservation_date' => $this->reservation->reservation_date,
            'doctor_name' => $this->reservation->doctor->name,
        ];
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
