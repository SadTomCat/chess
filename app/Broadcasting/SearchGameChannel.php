<?php

namespace App\Broadcasting;

use App\Models\User;
use App\Services\GameService;
use App\Websockets\IWebsocketManager;
use Illuminate\Support\Facades\Auth;

class SearchGameChannel
{
    /**
     * Authenticate the user's access to the channel.
     *
     * @param \App\Models\User $user
     * @return array|bool
     * @throws \App\Exceptions\WebsocketControlException
     */
    public function join(User $user, $id)
    {
        $logged = Auth::check();
        $manager = app(IWebsocketManager::class);
        $usersInfo = $manager->getUsers('presence-search-game-' . $id);
        $val = array_values($usersInfo)[0];
        $alreadySearch = array_search($id, $val, true);
        $notInGame = GameService::notInGame($user);

        if (!$logged || $alreadySearch !== false || !$notInGame) {
            return false;
        }

        return ['id' => $user->id, 'name' => $user->name];
    }
}
