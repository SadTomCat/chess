<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperGame
 */
class Game extends Model
{
    use HasFactory;

    protected $fillable = ['token', 'start_at', 'end_at', 'winner_color'];

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return HasMany
     */
    public function chatMessages(): HasMany
    {
        return $this->hasMany(GameMessage::class);
    }

    /**
     * @return HasMany
     */
    public function moves(): HasMany
    {
        return $this->hasMany(GameMove::class);
    }

    /**
     * @param int $userId
     * @return string
     */
    public function getUserColor(int $userId): string
    {
        return $this->users()
                    ->where('user_id', $userId)
                    ->withPivot(['color'])
                    ->first()->pivot->color;
    }

    /**
     * @param string $token
     * @return Model|Builder|Game
     */
    public static function getGameByToken(string $token): Model|Builder|Game
    {
        return static::where('token', $token)->firstOrFail();
    }

    /**
     * @param int $userId
     * @param int $page
     * @return array => ['items', 'total', 'last_page', 'current_page']
     */
    public static function paginateForUser(int $userId, int $page): array
    {
        $columns = ['games.id', 'start_at', 'end_at', 'winner_color', 'token', 'color'];

        $paginated = self::join('game_user', 'game_user.game_id', '=', 'games.id')
                         ->where('game_user.user_id', $userId)
                         ->latest()
                         ->paginate(10, $columns, page: $page);

        return [
            'items'        => $paginated->items(),
            'total'        => $paginated->total(),
            'last_page'    => $paginated->lastPage(),
            'current_page' => $paginated->currentPage(),
        ];
    }
}
