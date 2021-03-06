<?php

use App\Http\Controllers\Game\GameChatController;
use App\Http\Controllers\Game\GameMoveController;
use App\Http\Controllers\Game\UserJoinedToGame;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'belongs.game', 'game.not.ended'])->group(function () {
    Route::post('/game/{token}/join', UserJoinedToGame::class);

    Route::post('/game/{token}/move', GameMoveController::class);

    Route::post('/game/{token}/message', [GameChatController::class, 'newMessage']);
});
