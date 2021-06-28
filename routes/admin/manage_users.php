<?php

use App\Http\Controllers\Admin\AdminUserController;

Route::get('/users/{user}', [AdminUserController::class, 'show'])
     ->middleware('roles:admin,moderator,support');

Route::post('/block/{user}', [AdminUserController::class, 'block'])
    ->middleware('roles:admin,moderator');

Route::post('/unblock/{user}', [AdminUserController::class, 'unblock'])
    ->middleware('roles:admin,moderator');
