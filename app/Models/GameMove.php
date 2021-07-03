<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperGameMove
 */
class GameMove extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'game_id', 'type', 'from', 'to', 'created_at'];

    protected $casts = [
        'from' => 'array',
        'to'   => 'array',
    ];

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * @return HasOne
     */
    public function game(): HasOne
    {
        return $this->hasOne(Game::class);
    }
}
