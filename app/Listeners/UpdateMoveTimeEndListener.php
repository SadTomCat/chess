<?php

namespace App\Listeners;

use App\Jobs\MoveTimeEndJob;
use App\Models\Game;
use App\Services\MoveTimeEndService;

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
        MoveTimeEndService::deleteLastAndAddNew($game->id, $job);
    }
}
