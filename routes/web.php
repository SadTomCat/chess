<?php

use App\Http\Controllers\Admin\AdminGameController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\GameChatController;
use App\Http\Controllers\GameMoveController;
use App\Http\Controllers\PaginateUserGamesController;
use App\Http\Controllers\RuleCategoriesController;
use App\Http\Controllers\TablePaginationController;
use App\Http\Controllers\UserJoinedToGame;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SubscribedOnChannelController;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

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
    Route::middleware('auth')->post('/paginate-user-games/{user}', PaginateUserGamesController::class);

    Route::resource('/rule-categories', RuleCategoriesController::class)
         ->only(['index', 'store', 'destroy', 'update']);
});

Route::middleware('auth')->prefix('/api/admin')->group(function () {
    Route::post('/table/{table}/pagination', [TablePaginationController::class, 'tablePagination']);

    Route::post('/table/{table}/search', [TablePaginationController::class, 'searchInTable']);

    Route::post('/game/{game}', [AdminGameController::class, 'getInfo']);

    Route::post('/user/{user}', [AdminUserController::class, 'getInfo']);

    Route::post('/block/{user}', [AdminUserController::class, 'block']);

    Route::post('/unblock/{user}', [AdminUserController::class, 'unblock']);
});

Route::post('/subscribed/{channel}', [SubscribedOnChannelController::class, 'subscribed'])
     ->middleware('auth');

Route::post('/get-time', function () {
    return response()->json(['time' => date('U')]);
});

Route::middleware(['auth', 'belongs.game', 'game.not.ended'])->group(function () {
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

