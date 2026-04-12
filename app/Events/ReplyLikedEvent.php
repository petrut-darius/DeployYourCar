<?php

namespace App\Events;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReplyLikedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(private User $liker, private Reply $reply)
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
            new PrivateChannel("User.{$this->reply->user->id}.notifications"),
        ];
    }

    public function broadcastWith(): array {
        return [
            "liker" => [
                "id" => $this->liker->id,
                "name" => $this->liker->name,
            ],
            "reply" => [
                "id" => $this->reply->id,
                "content" => substr($this->reply->content,0,10),
            ]
        ];
    }

    public function broadcastAs(): string {
        return "ReplyLikedEvent";
    }
}
