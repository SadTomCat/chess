<?php

namespace App\Game\Chessmen;

use App\Game\MoveInfo;
use phpDocumentor\Reflection\DocBlock\Tags\Param;

class Pawn extends AbstractChessman
{
    /**
     * @param array $to
     * @return MoveInfo
     */
    public function validMoveByRule(array $to): MoveInfo
    {
        $dir = $this->color === 'white' ? -1 : 1;

        /* Back or only horizontal */
        if (($this->color === 'white' && $this->pos['x'] <= $to['x'])
            || ($this->color === 'black' && $this->pos['x'] >= $to['x'])) {
            return $this->createMoveInfo($to, false, 'You try move back or horizontal');
        }

        /* One x */
        if (($this->pos['x'] - $to['x'] === -$dir)) {
            return $this->createMoveInfo($to, $this->oneX($to));
        }

        /* Two x */
        if (($this->pos['x'] - $to['x'] === -$dir * 2) && $this->pos['y'] === $to['y']) {
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
            $toChessman = $this->board->getChessman($to);

            if (($toChessman instanceof NullChessman) && $this->takingOnAisle($to)) {
                $this->moveType = 'aisle';
                return true;
            }

            $status = !($toChessman instanceof NullChessman) && $toChessman->getColor() !== $this->color;
            $this->wrongMoveMessage = $status ? '' : 'You can move so if you capture opponent chessman';

            return $status;
        }

        $this->wrongMoveMessage = 'You cannot move so';
        return false;
    }

    /**
     * Before using this method make sure the $to cell is empty
     *
     * @param array $to
     * @return bool
     */
    private function takingOnAisle(array $to): bool
    {
        $lastMoveChessman = $this->board->getLastMoveChessman();
        $lastMoveChessmanPos = $lastMoveChessman->getPosition();

        if (($lastMoveChessman instanceof self) === false || $to['y'] !== $lastMoveChessmanPos['y']) {
            return false;
        }

        $lastMove = $this->board->getLastMoveInfo();
        $lastMoveFrom = $lastMove->getFrom();
        $lastMoveTo = $lastMove->getTo();

        return abs($lastMoveFrom['x'] - $lastMoveTo['x']) === 2;
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

        $status = ($this->board->getChessman($to) instanceof NullChessman);
        $this->wrongMoveMessage = 'You cannot move so';

        return $status;
    }

    /**
     * @return bool
     */
    private function isStartPos(): bool
    {
        return ($this->color === 'white' && $this->pos['x'] === 6)
            || ($this->color === 'black' && $this->pos['x'] === 1);
    }

    /**
     * @throws \Exception
     */
    public function canMoveSomewhere(): bool
    {
        $dir = $this->color === 'white' ? -1 : 1;

        $moves = [
            ['x' => $this->pos['x'] + $dir, 'y' => $this->pos['y']],
            ['x' => $this->pos['x'] + $dir, 'y' => $this->pos['y'] + 1],
            ['x' => $this->pos['x'] + $dir, 'y' => $this->pos['y'] - 1],
        ];

        return $this->canAnyByMoves($moves);
    }
}
