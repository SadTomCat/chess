<?php

namespace App\Game\Chessmen;

use App\Game\MoveInfo;

class Bishop extends AbstractChessman
{
    /**
     * @param array $to
     * @return MoveInfo
     */
    public function canMove(array $to): MoveInfo
    {
        return $this->createMoveInfo($to, $this->canDiagonal($to));
    }
}
