<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperRuleCategory
 */
class RuleCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * @return HasOne
     */
    public function rule(): HasOne
    {
        return $this->hasOne(Rule::class, 'rule_category', 'name');
    }
}
