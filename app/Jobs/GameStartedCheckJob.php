<?php

namespace App\Jobs;

use App\Events\GameNotStartedEvent;
use App\Game\GameTimings;
use App\Models\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GameStartedCheckJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public string $gameToken)
    {
        $this->delay = GameTimings::GAME_STARTED_CHECK;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $game = Game::getGameByToken($this->gameToken);

        if ($game->start_at === null) {
            broadcast(new GameNotStartedEvent($this->gameToken));
        }
    }
}
