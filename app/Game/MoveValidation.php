<?php

namespace App\Game;

use App\Game\Chessmen\AbstractChessman;
use App\Models\Game;
use Illuminate\Support\Str;

class MoveValidation
{
    public const NOT_YOU_MOVE = 'Not you move';

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

        $canMove = $this->fromChessman->canMove($this->to);

        if ($canMove->getStatus() === false) {
            return $canMove;
        }


        $boardAfterMove = $this->board->createAfterMove($this->from, $this->to);
        $kingAfterMove = $boardAfterMove->getKing($color);

        if (empty($kingAfterMove->inSafety()) === false) {
            return (new MoveInfo(status: false, message: "Dangerous move"));
        }

        return $canMove;
    }
}
