<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameNewMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;

    public string $gameToken;

    public string $message;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param string $gameToken
     * @param string $message
     */
    public function __construct(User $user, string $gameToken, string $message)
    {
        $this->user = $user;
        $this->gameToken = $gameToken;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('game-' . $this->gameToken);
    }

    /**
     * @return string[]
     */
    public function broadcastWith(): array
    {
        return ['message' => $this->message];
    }
}
