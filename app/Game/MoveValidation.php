<?php

namespace App\Game;

use App\Game\Chessmen\AbstractChessman;
use App\Models\Game;
use Illuminate\Support\Str;
use function Symfony\Component\Translation\t;

class MoveValidation
{
    public const NOT_YOU_MOVE = 'Not you move';

    public const IN_CHECK = 'Your king in check';

    private AbstractChessman $fromChessman;

    private GameBoard $board;

    public function __construct(
        private int $userId,
        private Game $game,
        private array $from,
        private array $to)
    {
        $this->board = GameBoard::createByGame($game);
        $this->fromChessman = $this->board->getChessman($from);
    }

    /**
     * @return MoveInfo
     * @throws \Exception
     */
    public function validate(): MoveInfo
    {
        $color = $this->game->users()
            ->where('user_id', $this->userId)
            ->withPivot('color')
            ->first()->pivot->color;

        $moveCount = $this->game->moves()->count();

        if (($moveCount % 2 === 0 && $color === 'black') || ($moveCount % 2 !== 0 && $color === 'white')
            || $color !== $this->fromChessman->getColor()) {
            return (new MoveInfo(status: false, message: self::NOT_YOU_MOVE));
        }

        return $this->fromChessman->moveValidation($this->to);
    }
}
