<?php

namespace App\Listeners;

use App\Models\Game;

class GameEndListener
{
    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $game = Game::getGameByToken($event->gameToken);
        $game->update([
            'end_at'       => date('Y-m-d H:i:s'),
            'winner_color' => $event->winnerColor,
        ]);
    }
}
