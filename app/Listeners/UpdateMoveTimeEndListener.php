<?php

namespace App\Listeners;

use App\Jobs\Managers\MoveTimeEndJobManager;
use App\Jobs\MoveTimeEndJob;
use App\Models\Game;

class UpdateMoveTimeEndListener
{
    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     * @throws \Throwable
     */
    public function handle($event)
    {
        $game = Game::getGameByToken($event->gameToken);
        $job = new MoveTimeEndJob($game);
        MoveTimeEndJobManager::deleteLastAndAddNew($game->id, $job);
    }
}
