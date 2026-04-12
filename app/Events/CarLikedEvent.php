<?php

namespace App\Events;

use App\Models\Car;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CarLikedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(private User $liker, private Car $car)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {   
        return [
            new PrivateChannel("User.{$this->car->owner->id}.notifications"),
        ];
    }

    public function broadcastWith(): array {
        return [
            "liker" => [
                "id" => $this->liker->id,
                "name" => $this->liker->name,
            ],
            "car" => [
                "id" => $this->car->id,
                "manufacture" => $this->car->manufacture,
                "model" => $this->car->model,
            ]
        ];
    }

    public function broadcastAs(): string {
        return "CarLikedEvent";
    }
}
