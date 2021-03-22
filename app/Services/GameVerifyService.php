<?php

namespace App\Services;

use App\Models\User;
use App\Websockets\IWebsocketManager;

class GameVerifyService
{
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
