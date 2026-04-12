<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowingCarCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            "type" => "newFollower",
            "message" => "{$this->commenter->name} wrote something about your car: {$this->car->manufacture} {$this->car->model}",
            "commenter_id" => $this->commenter->id,
            "commenter_name" => $this->commenter->name,
        ];
    }

    public function initialDatabaseReadAtValue(): ?Carbon {
        return null;
    }
}
