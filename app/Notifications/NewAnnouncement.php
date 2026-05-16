<?php


namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewAnnouncement extends Notification
{
    use Queueable;

    protected $announcement;

    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    public function via($notifiable)
    {
        $channels = ['database'];

        // Add email for high priority announcements
        if ($this->announcement->priority === 'high' || $this->announcement->priority === 'urgent') {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('[RSCB] ' . $this->announcement->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new ' . $this->announcement->priority . ' priority announcement has been published.')
            ->line($this->announcement->title)
            ->line(\Str::limit(strip_tags($this->announcement->content), 200))
            ->action('View Announcement', route('announcements.details', $this->announcement->id))
            ->line('Please take necessary action if required.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->announcement->title,
            'type' => 'announcement',
            'priority' => $this->announcement->priority,
            'announcement_id' => $this->announcement->id,
            'message' => $this->announcement->title,
        ];
    }
}
