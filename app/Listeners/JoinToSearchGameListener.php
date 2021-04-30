<?php

namespace App\Listeners;

use App\Events\GameFoundEvent;
use App\Jobs\GameStartedCheckJob;
use App\Models\Game;
use App\Models\User;
use App\Services\GameService;
use App\Websockets\IWebsocketManager;

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
    public function handle($event): void
    {
        $channels = $this->manager->getAllPresenceChannels('search-game')['presence'];

        if (count($channels) < 2) {
            return;
        }

        $mapChannels = $this->getMapChannel($channels);

        $userW = User::find($mapChannels[0]);
        $userB = User::find($mapChannels[1]);

        if (($userW === $userB) || !GameService::notInGame($userW) || !GameService::notInGame($userB)) {
            return;
        }

        $token = $this->createGame($userW->id, $userB->id);

        event(new GameFoundEvent($userW, $token));
        event(new GameFoundEvent($userB, $token));
        dispatch(new GameStartedCheckJob($token));
    }

    /**
     * @param array $channels
     * @return array|string[]|\string[][]
     */
    private function getMapChannel(array $channels): array
    {
        return array_map(static function ($el) {
            return str_replace('presence-search-game-', '', $el);
        }, $channels);
    }

    /**
     * Create token and return token
     *
     * @param $userW
     * @param $userB
     * @return string
     */
    private function createGame(int $userW, int $userB): string
    {
        $token = date('YmdHis') . "-W-$userW-B-$userB";

        $game = Game::create(['token' => $token]);

        $game->users()->sync([
            $userW => ['color' => 'white'],
            $userB => ['color' => 'black'],
        ]);

        return $token;
    }
}
