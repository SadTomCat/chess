<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GameMove extends Model
{
    use HasFactory;

    /**
     * @return HasOne
     */
    public function game(): HasOne
    {
        return $this->hasOne(Game::class);
    }
}
