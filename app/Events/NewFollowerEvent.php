<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class NewFollowerEvent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(private $user, private $followerId, private $followedId)
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
        \Log::info('Broadcasting on channel: User.' . $this->followedId . '.notifications');
        \Log::info('Reverb port: ' . config('broadcasting.connections.reverb.options.port'));
        
        return [
            new PrivateChannel("User.{$this->followedId}.notifications"),
        ];
    }

    public function broadcastWith(): array {
        return [
            "follower" => [
                "id" => $this->user->id,
                "name" => $this->user->name,
            ],
        ];
    }

    public function broadcastAs(): string {
        return "NewFollowerEvent";
    }
}
