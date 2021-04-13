<?php

namespace App\Game\Chessmen;

use App\Game\MoveInfo;

class King extends AbstractChessman
{
    /**
     * @param array $to
     * @return MoveInfo
     */
    public function validMoveByRule(array $to): MoveInfo
    {
        $difX = $this->pos['x'] - $to['x'];
        $difY = $this->pos['y'] - $to['y'];

        // castling
        if ($difX === 0 && abs($difY) === 2) {
            return $this->canCastling($to);
        }

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

            if ($opp->validMoveByRule($this->pos)->getStatus()) {
                $canCapture[] = $oppPos;
            }
        }

        return $canCapture;
    }

    /**
     * canMoveSomewhere check exists at least one move for king and automatically check king will be in safe.
     *
     * @return bool
     * @throws \Exception
     */
    public function canMoveSomewhere(): bool
    {
        $moves = [
            ['x' => $this->pos['x'] - 1, 'y' => $this->pos['y'] - 1],
            ['x' => $this->pos['x'] - 1, 'y' => $this->pos['y']],
            ['x' => $this->pos['x'] - 1, 'y' => $this->pos['y'] + 1],
            ['x' => $this->pos['x'], 'y' => $this->pos['y'] + 1],
            ['x' => $this->pos['x'] + 1, 'y' => $this->pos['y'] + 1],
            ['x' => $this->pos['x'] + 1, 'y' => $this->pos['y']],
            ['x' => $this->pos['x'] + 1, 'y' => $this->pos['y'] - 1],
            ['x' => $this->pos['x'], 'y' => $this->pos['y'] - 1],
        ];

        return $this->canAnyByMoves($moves);
    }

    public function willKingSafeAfterMove(array $to): bool
    {
        $kingAfterMove = $this->board
            ->createAfterMove($this->pos, $to)
            ->getKing($this->color);

        return empty($kingAfterMove->inSafety());
    }

    public function safetyMoveOnHorizontal(array $kingPos): bool
    {
        return false;
    }

    public function safetyMoveOnVertical(array $kingPos): bool
    {
        return false;
    }

    public function safetyMoveOnMainDiagonal(array $kingPos): bool
    {
        return false;
    }

    public function safetyMoveOnAntiDiagonal(array $kingPos): bool
    {
        return false;
    }

    /**
     * @param array $to
     * @return MoveInfo
     */
    private function canCastling(array $to): MoveInfo
    {
        if (empty($this->inSafety()) === false) {
            return $this->createMoveInfo($to, false, 'You in check');
        }

        $wing = $to['y'] === 6 ? 'r' : 'l';
        $endY = $wing === 'r' ? 6 : 1;

        $toChessman = $this->board->getChessman([
            'x' => $to['x'],
            'y' => $endY === 6 ? 7 : 0,
        ]);

        if (($toChessman instanceof Rook) === false || $this->color !== $toChessman->getColor()) {
            return $this->createMoveInfo($to, false, 'You can not castling so');
        }

        if ($this->canHorizontal(['x' => $to['x'], 'y' => $endY]) === false) {
            return $this->createMoveInfo($to, false, $this->wrongMoveMessage);
        }

        $isCastlingAvailable = $this->board->isCastlingAvailable($this->color, $wing);

        if ($isCastlingAvailable === true) {
            $this->moveType = 'castling';
            return $this->createMoveInfo($to);
        }

        return $this->createMoveInfo($to, false, 'Castling not available');
    }
}
