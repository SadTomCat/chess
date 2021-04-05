<?php

namespace App\Game\Chessmen;

use App\Game\MoveInfo;

class Knight extends AbstractChessman
{
    /**
     * @param array $to
     * @return MoveInfo
     */
    public function canMove(array $to): MoveInfo
    {
        if (!$this->checkToPos($to)) {
            return $this->createMoveInfo($to, false);
        }

        $difX = $this->pos['x'] - $to['x'];
        $difY = $this->pos['y'] - $to['y'];

        $status = (abs($difX) === 2 && abs($difY) === 1) || (abs($difX) === 1 && abs($difY) === 2);

        return $this->createMoveInfo($to, $status, 'Knight can not move so');
    }
}
