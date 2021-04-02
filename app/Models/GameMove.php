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

    protected $fillable = ['user_id', 'game_id', 'type', 'from', 'to'];

    /**
     * @return HasOne
     */
    public function game(): HasOne
    {
        return $this->hasOne(Game::class);
    }

    /**
     * @param $value
     */
    public function setFromAttribute($value): void
    {
        $this->attributes['from'] = json_encode($value);
    }

    /**
     * @param $value
     */
    public function setToAttribute($value): void
    {
        $this->attributes['to'] = json_encode($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getFromAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getToAttribute($value)
    {
        return json_decode($value);
    }
}
