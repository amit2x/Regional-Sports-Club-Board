<?php

namespace App\Notifications;

use App\Models\EventRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RegistrationRejected extends Notification
{
    use Queueable;

    protected $registration;

    public function __construct(EventRegistration $registration)
    {
        $this->registration = $registration;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Registration Update - ' . $this->registration->event->event_name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your registration for the event "' . $this->registration->event->event_name . '" has been rejected.')
            ->line('Registration Number: ' . $this->registration->registration_number)
            ->line('Reason: ' . $this->registration->rejection_reason)
            ->action('View Details', route('employee.registrations.show', $this->registration->id))
            ->line('You may contact the sports secretary for more information.');
    }

    public function toArray($notifiable)
    {
        return [
            'registration_id' => $this->registration->id,
            'event_name' => $this->registration->event->event_name,
            'status' => 'rejected',
            'message' => 'Your registration has been rejected. Reason: ' . $this->registration->rejection_reason,
        ];
    }
}
