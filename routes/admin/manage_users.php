<?php

use App\Http\Controllers\Admin\AdminUserController;

Route::get('/users/{user}', [AdminUserController::class, 'show']);

Route::post('/block/{user}', [AdminUserController::class, 'block']);

Route::post('/unblock/{user}', [AdminUserController::class, 'unblock']);
