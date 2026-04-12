<?php

namespace App\Notifications;

use App\Models\Car;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class CarComment extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private User $commenter, private Car $car)
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
            "type" => "carComment",
            "message" => "{$this->commenter->name} wrote something about your car: {$this->car->manufacture} {$this->car->model}",
            "commenter_id" => $this->commenter->id,
            "commenter_name" => $this->commenter->name,
        ];
    }

    public function initialDatabaseReadAtValue(): ?Carbon {
        return null;
    }
}
