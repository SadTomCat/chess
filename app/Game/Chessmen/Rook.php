<?php

namespace App\Game\Chessmen;

use App\Game\MoveInfo;

class Rook extends AbstractChessman
{
    /**
     * @param array $to
     * @return MoveInfo
     */
    public function canMove(array $to): MoveInfo
    {
        if ($to['x'] === $this->pos['x']) {
            return $this->createMoveInfo($to, $this->canHorizontal($to));
        }

        if ($to['y'] === $this->pos['y']) {
            return $this->createMoveInfo($to, $this->canVertically($to));
        }

        return (new MoveInfo(
            status: false,
            message: 'You cannot move so'
        ));
    }
}
