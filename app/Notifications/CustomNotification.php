<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class CustomNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $type;
    protected $data;

    public function __construct($message, $type = 'general', $data = [])
    {
        $this->message = $message;
        $this->type = $type;
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('RSCB Notification')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($this->message)
            ->action('View Details', url('/'))
            ->line('Thank you for using RSCB!');
    }

    public function toArray($notifiable)
    {
        return array_merge([
            'message' => $this->message,
            'type' => $this->type,
        ], $this->data);
    }
}
