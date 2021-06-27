<?php

use App\Http\Controllers\Admin\AdminGameController;
use App\Http\Controllers\ImagesController;

Route::middleware('auth')->prefix('/api/admin')->group(function () {
    require __DIR__ . '/pagination.php';

    require __DIR__ . '/manage_users.php';

    Route::get('/games/{game}', [AdminGameController::class, 'show']);

    Route::post('/images/ckeditor', [ImagesController::class, 'uploadFromCkeditor']);
});
