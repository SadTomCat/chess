<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

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
        $sql = 'SELECT COUNT(*) as count FROM
                        (SELECT gu.`user_id`, gu.`game_id`, gu.`color`
                        FROM `game_user` gu
                        WHERE gu.user_id=:id) AS subq
                    INNER JOIN `games` g ON g.id=subq.game_id
                    WHERE g.winner_color=subq.color';

        return DB::select($sql, ['id' => $this->id])[0]->count;
    }
}
