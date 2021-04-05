<?php

namespace App\Game;

use App\Game\Chessmen\AbstractChessman;
use App\Models\Game;

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
     */
    public function validate(): MoveInfo
    {
        $userColor = $this->game->users()
            ->where('user_id', $this->userId)
            ->withPivot('color')
            ->first()->pivot->color;

        $moveCount = $this->game->moves()->count();

        if (($moveCount % 2 === 0 && $userColor === 'black') || ($moveCount % 2 !== 0 && $userColor === 'white')
            || $userColor !== $this->fromChessman->getColor()) {
            return (new MoveInfo(status: false, message: self::NOT_YOU_MOVE));
        }

        return $this->fromChessman->canMove($this->to);
    }
}
