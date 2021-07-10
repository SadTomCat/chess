<?php

namespace App\Http\Middleware;

use App\Models\Game;
use Closure;
use Illuminate\Http\Request;

class GameNotEnded
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->token;
        $game = Game::getGameByToken($token);

        if ($game->end_at !== null) {
            abort(403, 'Game has ended');
        }

        return $next($request);
    }
}
