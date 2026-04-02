<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class EventNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
   
    public function via($notifiable)
    {
        return ['database']; // important for bell
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
{
    return [
        'event_id' => $this->event->id,
        'title' => $this->event->title,
        'description' => $this->event->description,
        'start_time' => $this->event->start_time,
        'end_time' => $this->event->end_time,
        'location' => $this->event->location,
        'venue' => $this->event->venue,
        'created_by' => $this->event->created_by,
    ];
}
}
