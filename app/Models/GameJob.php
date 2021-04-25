<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperGameJob
 */
class GameJob extends Model
{
    use HasFactory;

    protected $fillable = ['job_type', 'job_id', 'game_id'];
}
