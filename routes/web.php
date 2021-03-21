<?php

use App\Events\JoinToGameEvent;
use App\Events\JoinToSearchGameEvent;
use App\Http\Controllers\SubscribedOnChannelController;
use App\Models\User;
use App\Websockets\IWebsocketManager;
use Illuminate\Support\Facades\Route;
use App\Websockets\PusherManager;

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

Route::get('/{path}', function () {
    return view('index');
})->where('path', '.*');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';
