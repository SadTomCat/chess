<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperRuleCategory
 */
class RuleCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
}
