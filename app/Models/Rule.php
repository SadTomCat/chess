<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperRule
 */
class Rule extends Model
{
    use HasFactory;

    protected $fillable = ['rule_category', 'content'];

    /**
     * @param $category
     * @return Rule|Model|Builder
     * @throws ModelNotFoundException
     */
    public static function getByRuleCategory($category): Rule|Model|Builder
    {
        return static::where('rule_category', $category)->firstOrFail();
    }

    /**
     * @return BelongsTo
     */
    public function ruleCategory(): BelongsTo
    {
        return $this->belongsTo(RuleCategory::class, 'rule_category', 'name');
    }
}
