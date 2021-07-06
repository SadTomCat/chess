<?php

use App\Http\Controllers\Admin\AdminUserController;

Route::get('/users/{user}', [AdminUserController::class, 'show'])
     ->middleware('roles:admin,moderator,support');

Route::post('/block/{user}', [AdminUserController::class, 'block'])
     ->middleware('roles:admin,moderator');

Route::post('/unblock/{user}', [AdminUserController::class, 'unblock'])
     ->middleware('roles:admin,moderator');

Route::patch('/users/{user}/roles', [AdminUserController::class, 'updateRole'])
     ->middleware('roles:admin,moderator')
     ->name('admin.update.user.role');

Route::post('/users/create', [AdminUserController::class, 'createUser'])
     ->middleware('roles:admin')
     ->name('admin.users.create');
