<?php

namespace App\Game\Chessmen;

use App\Game\GameBoard;
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

    /**
     * Return all opponent chessmen positions that can capture the king.
     *
     * @return array
     */
    public function inSafety(): array
    {
        $canCapture = [];
        $oppColor = $this->color === 'white' ? 'black' : 'white';
        $oppChessmen = $this->board->getAllChessmenByColor($oppColor);

        foreach ($oppChessmen as $oppPos) {
            $opp = $this->board->getChessman($oppPos);

            if ($opp->canMove($this->pos)->getStatus()) {
                $canCapture[] = $oppPos;
            }
        }

        return $canCapture;
    }
}
