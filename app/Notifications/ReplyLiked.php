<?php

namespace App\Notifications;

use App\Models\Car;
use App\Models\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class ReplyLiked extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private User $commenter, private ?Car $car, private Reply $reply)
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
        $carContext = $this->car
            ? "on the {$this->car->manufacture} {$this->car->model}"
            : ""; 

        return [
            "type" => "replyLiked",
            "message" => "{$this->commenter->name} wrote something about your comment {$carContext}",
            "commenter_id" => $this->commenter->id,
            "commenter_name" => $this->commenter->name,
            "reply_id" => $this->reply->id,
        ];
    }

    public function initialDatabaseReadAtValue(): ?Carbon {
        return null;
    }
}
