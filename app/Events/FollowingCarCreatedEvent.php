<?php

namespace App\Events;

use App\Models\Car;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FollowingCarCreatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(private User $followed, private Car $car, private User $user)
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
            new PrivateChannel("User.{$this->user->id}.notifications"),
        ];
    }

    public function broadcastWith(): array {
        return [
            "following" => [
                "id" => $this->followed->id,
                "name" => $this->followed->name,
            ],
            "car" => [
                "id" => $this->car->id,
                "manufacture" => $this->car->manufacture,
                "model" => $this->car->model,
            ],
        ];
    }

    public function broadcastAs(): string {
        return "FollowingCarCreatedEvent";
    }
}
