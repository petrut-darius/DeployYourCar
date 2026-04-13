<?php

namespace App\Notifications;

use App\Models\Car;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class FollowingCarUpdated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private User $followed, private Car $car)
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
        return ['database'];
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
            "message" => "{$this->followed->name} has just updated a their car: {$this->car->manufacture} {$this->car->model}",
            "followed_id" => $this->followed->id,
            "followed_name" => $this->followed->name,
        ];
    }

    public function initialDatabaseReadAtValue(): ?Carbon {
        return null;
    }
}
