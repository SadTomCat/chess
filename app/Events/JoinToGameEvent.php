<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JoinToGameEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $gameToken;

    /**
     * Create a new event instance.
     *
     * @param $user
     * @param string $gameToken
     */
    public function __construct($user, string $gameToken)
    {
        $this->user = $user;
        $this->gameToken = $gameToken;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('search-game-' . $this->user['id']);
    }

    public function broadcastWith()
    {
        return ['gameToken' => $this->gameToken];
    }
}
