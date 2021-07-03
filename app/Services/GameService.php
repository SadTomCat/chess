<?php

namespace App\Services;

use App\Events\GameEndEvent;
use App\Events\GameMoveEvent;
use App\Game\GameTimings;
use App\Game\MoveInfo;
use App\Models\Game;
use App\Models\GameMove;
use App\Models\User;
use App\Websockets\IWebsocketManager;
use JetBrains\PhpStorm\ArrayShape;

class GameService
{
    public function __construct(protected Game $game)
    {
    }

    /**
     * @return array ['moves' => "array", 'game' => "array"]
     */
    public function getGameInfo(): array
    {
        return [
            'moves' => $this->game->moves()->get(['from', 'to', 'type'])->toArray(),
            'game'  => $this->game->only(['id', 'token', 'start_at', 'end_at', 'winner_color']),
        ];
    }

    /**
     * @param Game $game
     * @param User $user
     * @param MoveInfo $moveInfo
     */
    public static function successfulMove(Game $game, User $user, MoveInfo $moveInfo): void
    {
        $move = $moveInfo->getMoveArray();
        $token = $game->token;

        GameMove::create(array_merge(['user_id' => $user->id, 'game_id' => $game->id,], $move));

        if ($moveInfo->getType() === 'mate') {
            broadcast(new GameEndEvent($game->token, $game->getUserColor($user->id), $move));
            return;
        }

        $endAt = (int)date('U') + GameTimings::MOVE_TIME_WITH_DELAY;
        broadcast(new GameMoveEvent($token, $move, $endAt));
    }

    /**
     * Check user is in play or not.
     *
     * @param User $user
     * @param string $currentToken - current game
     * @return bool
     */
    public static function notInGame(User $user, string $currentToken = ''): bool
    {
        $token = $user->games()->latest()->first()?->token;
        $currentToken = '' ?: 'presence-game-' . $currentToken;

        if ($token === null || $currentToken === $token) {
            return true;
        }

        $manager = app(IWebsocketManager::class);
        $lastGameChannel = $manager->getUsers('presence-game-' . $token)['presence-game-' . $token];
        $userInGame = array_search($user->id, $lastGameChannel, false);

        return $userInGame === false;
    }
}
