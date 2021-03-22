<?php

use App\Broadcasting\GameChannel;
use App\Broadcasting\SearchGameChannel;
use App\Models\Game;
use App\Models\User;
use App\Services\GameVerifyService;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

Broadcast::channel('search-game-{id}', SearchGameChannel::class);

Broadcast::channel('game-{token}', GameChannel::class);
