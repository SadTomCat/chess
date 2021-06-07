<?php

use App\Http\Controllers\Admin\AdminGameController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\GameChatController;
use App\Http\Controllers\GameMoveController;
use App\Http\Controllers\UserGamesController;
use App\Http\Controllers\RuleCategoriesController;
use App\Http\Controllers\TablePaginationController;
use App\Http\Controllers\UserJoinedToGame;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SubscribedOnChannelController;
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

Route::prefix('/api')->group(function () {
    Route::get('/time', fn() => response()->json(['time' => date('U')]));

    Route::patch('/settings', [SettingsController::class, 'update'])->middleware('auth');

    Route::resource('/rule-categories', RuleCategoriesController::class)
         ->only(['index', 'store', 'destroy', 'update']);

    Route::middleware('auth')->group(function () {
        Route::get('/users/{user}/games/paginated', [UserGamesController::class, 'paginate']);

        Route::post('/channels/{channel}/subscribed', [SubscribedOnChannelController::class, 'subscribed']);
    });
});

Route::middleware('auth')->prefix('/api/admin')->group(function () {
    Route::get('/table/{table}/paginated', [TablePaginationController::class, 'tablePagination']);

    Route::get('/table/{table}/paginated/search', [TablePaginationController::class, 'searchInTable']);

    Route::get('/games/{game}', [AdminGameController::class, 'show']);

    Route::get('/users/{user}', [AdminUserController::class, 'show']);

    Route::post('/block/{user}', [AdminUserController::class, 'block']);

    Route::post('/unblock/{user}', [AdminUserController::class, 'unblock']);
});

// TODO: REST URL
Route::middleware(['auth', 'belongs.game', 'game.not.ended'])->group(function () {
    Route::post('/game/{token}/join', UserJoinedToGame::class);

    Route::post('/game/{token}/move', GameMoveController::class);

    Route::post('/game/{token}/message', [GameChatController::class, 'newMessage']);
});

require __DIR__ . '/auth.php';

// TODO: ADD regular expression
Route::get('/{path}', function () {
    return view('index');
})->where('path', '.*');

