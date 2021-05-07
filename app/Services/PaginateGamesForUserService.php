<?php

namespace App\Services;

use App\Models\Game;

class PaginateGamesForUserService
{
    /**
     * @param int $userId
     * @param int $page
     * @return array
     */
    public static function getPaginated(int $userId, int $page): array
    {
        $columns = ['games.id', 'start_at', 'end_at', 'winner_color', 'token', 'color'];

        $paginated = Game::join('game_user', 'game_user.game_id', '=', 'games.id')
                         ->where('game_user.user_id', $userId)
                         ->latest()
                         ->paginate(10, $columns, page: $page);

        return [
            'items' => $paginated->items(),
            'total' => $paginated->total(),
            'last_page' => $paginated->lastPage(),
            'current_page' => $paginated->currentPage(),
        ];
    }
}
