<?php

namespace App\Http\Controllers;

use App\Game\MoveValidation;
use App\Http\Requests\GameMoveRequest;
use App\Models\Game;
use App\Models\User;
use App\Services\GameService;
use App\Jobs\Managers\MoveTimeEndJobManager;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GameMoveController extends Controller
{
    /**
     * @var User
     */
    private User $user;

    /**
     * @var Game
     */
    private Game $game;

    /**
     * @param GameMoveRequest $request
     * @param string $token
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(GameMoveRequest $request, string $token): JsonResponse
    {
        $this->initial($request, $token);

        MoveTimeEndJobManager::softDeleteLast($this->game->id);

        $moveValidator = new MoveValidation($this->user->id, $this->game, ...$request->move);
        $moveInfo = $moveValidator->validate();

        if ($moveInfo->getStatus() === false) {
            MoveTimeEndJobManager::recoveryLast($this->game->id);
            return response()->json($moveInfo->getArrayFailed());
        }

        GameService::successfulMove($this->game, $this->user, $moveInfo);

        return response()->json(['status' => true]);
    }

    /**
     * @param Request $request
     * @param string $token
     */
    private function initial(Request $request, string $token): void
    {
        $this->user = $request->user();
        $this->game = $this->user->getGameByToken($token);
    }
}
