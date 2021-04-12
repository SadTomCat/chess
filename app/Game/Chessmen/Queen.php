<?php

namespace App\Game\Chessmen;

use App\Game\MoveInfo;

class Queen extends AbstractChessman
{
    /**
     * @param array $to
     * @return MoveInfo
     */
    public function validMoveByRule(array $to): MoveInfo
    {
        $difX = $this->pos['x'] - $to['x'];
        $difY = $this->pos['y'] - $to['y'];

        if (abs($difX) === abs($difY)) {
            return $this->createMoveInfo($to, $this->canDiagonal($to));
        }

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

    /**
     * @return bool
     * @throws \Exception
     */
    public function canMoveSomewhere(): bool
    {
        return $this->anyOnMainDiagonal() || $this->anyOnAntiDiagonal() || $this->anyOnHorizontal()
            || $this->anyOnVertical();
    }
}
