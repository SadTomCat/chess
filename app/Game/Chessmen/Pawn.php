<?php

namespace App\Game\Chessmen;

use App\Game\MoveInfo;

class Pawn extends AbstractChessman
{
    /**
     * @param array $to
     * @return MoveInfo
     */
    public function canMove(array $to): MoveInfo
    {
        $direction = $this->color === 'white' ? -1 : 1;

        /* Back or only horizontal */
        if (($this->color === 'white' && $this->pos['x'] <= $to['x'])
            || ($this->color === 'black' && $this->pos['x'] >= $to['x'])) {
            return $this->createMoveInfo($to, false, 'You try move back or horizontal');
        }

        /* One x */
        if (($this->pos['x'] - $to['x'] === -$direction)) {
            return $this->createMoveInfo($to, $this->oneX($to));
        }

        /* Two x */
        if (($this->pos['x'] - $to['x'] === -$direction * 2) && $this->pos['y'] === $to['y']) {
            return $this->createMoveInfo($to, $this->twoX($to));
        }

        return (new MoveInfo(
            status: false,
            message: 'You cannot move so because you just pawn'
        ));
    }

    /**
     * @param array $to
     * @return bool
     */
    private function oneX(array $to): bool
    {
        $difY = $this->pos['y'] - $to['y'];

        if ($difY === 0) {
            $status = $this->board->getChessman($to) instanceof NullChessman;
            $this->wrongMoveMessage = $status ? '' : 'You can not move because in this cell exist chessman';

            return $status;
        }

        /* capture */
        if ($difY === -1 || $difY === 1) {
            $status = !($this->board->getChessman($to) instanceof NullChessman);
            $this->wrongMoveMessage = $status ? '' : 'You can move so if you capture opponent chessman';

            return $status;
        }

        $this->wrongMoveMessage = 'You cannot move so';
        return false;
    }

    /**
     * @param array $to
     * @return bool
     */
    private function twoX(array $to): bool
    {
        if (!$this->isStartPos()) {
            $this->wrongMoveMessage = 'Pawn can move so if place in start position';
            return false;
        }

        return $this->checkToPos($to);
    }

    /**
     * @return bool
     */
    private function isStartPos(): bool
    {
        return ($this->color === 'white' && $this->pos['x'] === 6)
            || ($this->color === 'black' && $this->pos['x'] === 1);
    }
}
