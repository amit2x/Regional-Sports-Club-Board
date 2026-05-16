<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EventReminder extends Notification
{
    use Queueable;

    protected $event;
    protected $daysUntilEvent;

    public function __construct(Event $event, $daysUntilEvent)
    {
        $this->event = $event;
        $this->daysUntilEvent = $daysUntilEvent;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Event Reminder: ' . $this->event->event_name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('This is a reminder about the upcoming event:')
            ->line($this->event->event_name)
            ->line('Date: ' . $this->event->start_date->format('d M Y'))
            ->line('Venue: ' . $this->event->venue)
            ->line('The event is in ' . $this->daysUntilEvent . ' days.')
            ->action('View Event Details', route('events.details', $this->event->event_code));
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Event Reminder',
            'type' => 'event_reminder',
            'event_id' => $this->event->id,
            'days_until' => $this->daysUntilEvent,
            'message' => 'Reminder: ' . $this->event->event_name . ' is in ' . $this->daysUntilEvent . ' days.',
        ];
    }
}
