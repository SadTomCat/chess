<?php

namespace App\Models;

use App\Exceptions\ChessRuleException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @mixin IdeHelperRule
 */
class ChessRule extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'content', 'slug'];

    /**
     * @param string $str
     * @return string
     */
    public static function getSlug(string $str): string
    {
        return Str::slug($str);
    }

    /**
     * @param string $slug
     * @throws ModelNotFoundException
     * @return ChessRule|Model|Builder
     */
    public static function getBySlug(string $slug): ChessRule|Model|Builder
    {
        return static::where('slug', $slug)->firstOrFail();
    }

    /**
     * @param array $columns
     * @return Collection|array
     */
    public static function getFilled(array $columns = []): Collection|array
    {
        return self::where('content', '!=', null)->get($columns);
    }

    /**
     * @return bool|null
     * @throws ChessRuleException
     */
    public function deleteName(): ?bool
    {
        if ($this->content !== null) {
            throw new ChessRuleException("For $this->name exists content");
        }

        return $this->delete();
    }
}
