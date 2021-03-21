<?php

namespace App\Listeners;

use App\Events\JoinToGameEvent;
use App\Models\User;
use App\Websockets\IWebsocketManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use function Symfony\Component\Translation\t;

class JoinToSearchGameListener
{
    public IWebsocketManager $manager;

    /**
     * Create the event listener.
     *
     * @param IWebsocketManager $manager
     */
    public function __construct(IWebsocketManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $channels = $this->manager->getAllPresenceChannels('search-game')['presence'];

        if (count($channels) < 2) {
            return;
        }

        $mapChannels = array_map(function ($el) {
            return str_replace('presence-search-game-', '', $el);
        }, $channels);

        event(new JoinToGameEvent(User::find($mapChannels[0]), 'tempToken'));
        event(new JoinToGameEvent(User::find($mapChannels[1]), 'tempToken'));
    }
}
