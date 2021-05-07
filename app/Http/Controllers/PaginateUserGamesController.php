<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaginateGamesForUserRequest;
use App\Models\User;
use App\Services\PaginateGamesForUserService;

class PaginateUserGamesController extends Controller
{
    public function __invoke(PaginateGamesForUserRequest $request, User $user)
    {
        return PaginateGamesForUserService::getPaginated($user->id, $request->page);
    }
}
