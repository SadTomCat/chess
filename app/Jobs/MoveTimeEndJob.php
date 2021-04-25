<?php

namespace App\Jobs;

use App\Events\GameEndEvent;
use App\Models\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MoveTimeEndJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Game $game)
    {
        $this->delay = 125;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lastMoveUserId = $this->game->moves()->latest()->first(['user_id'])?->user_id;

        $winner = $lastMoveUserId === null ? 'black' : $this->game->getUserColor($lastMoveUserId);

        event(new GameEndEvent($this->game->token, $winner));
    }
}
