<?php

namespace App\Http\Middleware;

use App\Models\Game;
use Closure;
use Illuminate\Http\Request;

class BelongsToGame
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $user = $request->user();
        $token = $request->token;

        $game = Game::where('token', $token)->latest()->first();

        if ($game === null) {
            abort(400, 'Incorrect request');
        }

        $belongs = $game->users()->find($user->id);

        if ($belongs === null) {
            abort(401, 'You not belongs to this game');
        }

        return $next($request);
    }
}
