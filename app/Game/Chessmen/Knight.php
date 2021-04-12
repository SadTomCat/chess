<?php

namespace App\Game\Chessmen;

use App\Game\MoveInfo;

class Knight extends AbstractChessman
{
    /**
     * @param array $to
     * @return MoveInfo
     */
    public function validMoveByRule(array $to): MoveInfo
    {
        if (!$this->checkToPos($to)) {
            return $this->createMoveInfo($to, false);
        }

        $difX = $this->pos['x'] - $to['x'];
        $difY = $this->pos['y'] - $to['y'];

        $status = (abs($difX) === 2 && abs($difY) === 1) || (abs($difX) === 1 && abs($difY) === 2);

        return $this->createMoveInfo($to, $status, 'Knight can not move so');
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function canMoveSomewhere(): bool
    {
        $moves = [
            ['x' => $this->pos['x'] + 2, 'y' => $this->pos['y'] + 1],
            ['x' => $this->pos['x'] + 2, 'y' => $this->pos['y'] - 1],
            ['x' => $this->pos['x'] - 2, 'y' => $this->pos['y'] + 1],
            ['x' => $this->pos['x'] - 2, 'y' => $this->pos['y'] - 1],

            ['y' => $this->pos['y'] + 2, 'x' => $this->pos['x'] + 1],
            ['y' => $this->pos['y'] + 2, 'x' => $this->pos['x'] - 1],
            ['y' => $this->pos['y'] - 2, 'x' => $this->pos['x'] + 1],
            ['y' => $this->pos['y'] - 2, 'x' => $this->pos['x'] - 1],
        ];

        return $this->canAnyByMoves($moves);
    }
}
