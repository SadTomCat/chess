<?php

use App\Http\Controllers\Admin\AdminGamesController;
use App\Http\Controllers\ImagesController;

Route::middleware('auth')->prefix('/api/admin')->group(function () {
    require __DIR__ . '/pagination.php';

    require __DIR__ . '/manage_users.php';

    Route::get('/games/{game}', [AdminGamesController::class, 'showGameInfo'])
         ->middleware('roles:admin,moderator,support');

    Route::post('/images/ckeditor', [ImagesController::class, 'uploadFromCkeditor'])
        ->middleware('roles:admin,redactor');
});
