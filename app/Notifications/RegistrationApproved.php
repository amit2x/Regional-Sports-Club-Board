<?php

namespace App\Notifications;

use App\Models\EventRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RegistrationApproved extends Notification
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
            ->subject('Registration Approved - ' . $this->registration->event->event_name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your registration for the event "' . $this->registration->event->event_name . '" has been approved.')
            ->line('Registration Number: ' . $this->registration->registration_number)
            ->line('Event Date: ' . $this->registration->event->start_date->format('d M Y'))
            ->line('Venue: ' . $this->registration->event->venue)
            ->action('View Registration', route('employee.registrations.show', $this->registration->id))
            ->line('Thank you for participating!');
    }

    public function toArray($notifiable)
    {
        return [
            'registration_id' => $this->registration->id,
            'event_name' => $this->registration->event->event_name,
            'status' => 'approved',
            'message' => 'Your registration has been approved.',
        ];
    }
}
