<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JetBrains\PhpStorm\ArrayShape;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'blocked',
        'blocked_at',
        'email_verified_at',
    ];

    protected $visible = [
        'id',
        'name',
        'email',
        'role',
        'blocked',
        'blocked_at',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'blocked'           => 'boolean',
    ];

    protected $attributes = [
        'blocked' => false,
        'role'    => 'user',
    ];

    /**
     * @return BelongsToMany
     */
    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class)->withPivot('color');
    }

    /**
     * @return HasMany
     */
    public function gameChatMessages(): HasMany
    {
        return $this->hasMany(GameMessage::class);
    }

    /**
     * @param $token
     * @return Game
     */
    public function getGameByToken($token): Game
    {
        $game = $this->games()->where('token', $token)->first();

        if ($game === null) {
            throw new ModelNotFoundException();
        }

        return $game;
    }

    /**
     * @return int
     */
    public function countEndedGames(): int
    {
        return $this->games()->where('end_at', '!=', null)->count();
    }

    /**
     * @return int
     */
    public function countGamesWon(): int
    {
        return Game::join('game_user', 'games.id', '=', 'game_user.game_id')
                   ->where('game_user.user_id', '=', $this->id)
                   ->whereColumn('games.winner_color', '=', 'game_user.color')->count();
    }

    /**
     * @param array $only - games statistics columns will be set when you set $needGamesStatistics
     * @param bool $needGamesStatistics
     * @return array
     */
    public function getUserInfo(
        array $only = ['id', 'name', 'email', 'role', 'blocked'],
        bool $needGamesStatistics = false
    ): array
    {
        $info = $this->only($this->getVisible());

        if ($needGamesStatistics === true) {
            $gamesStatistics = $this->getGamesStatistics();
            $info = array_merge($info, $gamesStatistics);
            $only = [...$only, ...array_keys($gamesStatistics)];
        }

        return array_filter(
            $info,
            fn($v, $k) => in_array($k, $only, true) && $v !== null,
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * @return array ['count_games' => "int", 'count_won' => "int", 'not_count_games' => "int"]
     */
    public function getGamesStatistics(): array
    {
        return [
            'count_games'     => $this->games()->count(),
            'count_won'       => $this->countGamesWon(),
            'not_count_games' => $this->games()->where('end_at', null)->count()
        ];
    }
}
