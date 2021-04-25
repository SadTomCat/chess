<?php

use App\Http\Controllers\GameChatController;
use App\Http\Controllers\GameMoveController;
use App\Http\Controllers\UserJoinedToGame;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SubscribedOnChannelController;
use App\Websockets\IWebsocketManager;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
 * Route for test, need only while develop
 * */
Route::get('/websocket', function (IWebsocketManager $manager) {
    dd($manager->getAllPresenceChannels());
});

Route::post('/subscribed/{channel}', [SubscribedOnChannelController::class, 'subscribed'])
    ->middleware('auth');

Route::middleware(['auth', 'belongs.game'])->group(function () {
    Route::post('/game/{token}/join', UserJoinedToGame::class);

    Route::post('/game/{token}/move', GameMoveController::class);

    Route::post('/game/{token}/message', [GameChatController::class, 'newMessage']);
});

Route::post('/settings', [SettingsController::class, 'update'])
    ->middleware('auth');

require __DIR__ . '/auth.php';

Route::get('/{path}', function () {
    return view('index');
})->where('path', '.*');

