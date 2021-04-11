<?php

namespace App\Game\Chessmen;

use App\Game\GameBoard;
use App\Game\MoveInfo;

class NullChessman extends AbstractChessman
{
    /**
     * NullChessman constructor.
     * @param array $pos
     * @param string $color
     * @param GameBoard $board
     */
    public function __construct(array $pos, string $color, GameBoard $board)
    {
        $this->pos = $pos;
        $this->color = 'none';
        $this->board = $board;
    }

    /**
     * @param array $to
     * @return MoveInfo
     */
    public function canMove(array $to): MoveInfo
    {
        return (new MoveInfo(
            status: false,
            message: 'This is empty cell',
        ));
    }

    public function safetyMoveOnHorizontal(array $kingPos): bool
    {
        return false;
    }

    public function safetyMoveOnVertical(array $kingPos): bool
    {
        return false;
    }

    public function safetyMoveOnMainDiagonal(array $kingPos): bool
    {
        return false;
    }

    public function safetyMoveOnAntiDiagonal(array $kingPos): bool
    {
        return false;
    }
}
