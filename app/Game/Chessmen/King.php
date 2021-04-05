<?php

namespace App\Game\Chessmen;

use App\Game\MoveInfo;

class King extends AbstractChessman
{
    /**
     * @param array $to
     * @return MoveInfo
     */
    public function canMove(array $to): MoveInfo
    {
        $difX = $this->pos['x'] - $to['x'];
        $difY = $this->pos['y'] - $to['y'];

        if (!$this->checkToPos($to)) {
            return $this->createMoveInfo($to, false);
        }

        if (($difX > 1 || $difX < -1) || ($difY > 1 || $difY < -1)) {
            return $this->createMoveInfo($to, false, 'You can move only on one cell');
        }

        return $this->createMoveInfo($to);
    }
}
