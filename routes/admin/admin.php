<?php

use App\Helpers\RolesHelper;
use App\Http\Controllers\Admin\AdminGamesController;
use App\Http\Controllers\Admin\AdminRolesController;
use App\Http\Controllers\ImagesController;

Route::middleware('auth')->prefix('/api/admin')->group(function () {
    require __DIR__ . '/pagination.php';

    require __DIR__ . '/manage_users.php';

    Route::get('/roles', [AdminRolesController::class, 'getAccessRoles'])
         ->name('admin.get.access.roles')
         ->middleware("roles:" . app(RolesHelper::class)->getImplodedAuthorizedEditors(','));

    Route::get('/games/{game}', [AdminGamesController::class, 'showGameInfo'])
         ->middleware('roles:admin,moderator,support');

    Route::post('/images/ckeditor', [ImagesController::class, 'uploadFromCkeditor'])
         ->middleware('roles:admin,redactor');
});
