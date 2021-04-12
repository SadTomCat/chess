<?php

namespace App\Game\Chessmen;

use App\Game\MoveInfo;

class Bishop extends AbstractChessman
{
    /**
     * @param array $to
     * @return MoveInfo
     */
    public function validMoveByRule(array $to): MoveInfo
    {
        return $this->createMoveInfo($to, $this->canDiagonal($to));
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function canMoveSomewhere(): bool
    {
        return $this->anyOnMainDiagonal() || $this->anyOnAntiDiagonal();
    }
}
