<?php

use App\Http\Controllers\RuleCategoriesController;
use App\Http\Controllers\RulesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SubscribedOnChannelController;
use App\Http\Controllers\UserController;
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

    Route::resource('/rules/categories', RuleCategoriesController::class)
         ->only(['index', 'store', 'destroy', 'update']);

    Route::resource('/rules', RulesController::class)
         ->only(['index', 'show', 'store', 'destroy', 'update']);

    Route::middleware('auth')->group(function () {
        Route::get('/role', [UserController::class, 'getRole']);

        Route::get('/users/{user}/games/paginated', [UserController::class, 'paginateGames']);

        Route::get('/users/{user}/games/statistics', [UserController::class, 'getGamesStatistics']);

        Route::post('/channels/{channel}/subscribed', [SubscribedOnChannelController::class, 'subscribed']);
    });
});

require __DIR__ . '/game.php';

require __DIR__ . '/admin/admin.php';

require __DIR__ . '/auth.php';

Route::get('/{path}', function () {
    return view('index');
})->where('path', '.*');

