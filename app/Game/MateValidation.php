<?php

namespace App\Game;

use App\Game\Chessmen\AbstractChessman;
use App\Game\Chessmen\King;

class MateValidation
{
    private King $king;

    /**
     * @var AbstractChessman[]
     */
    private array $allies;

    /**
     * MateValidation constructor.
     *
     * @param GameBoard $gameBoard
     * @param string $color
     * @throws \Exception
     */
    public function __construct(private GameBoard $gameBoard, private string $color)
    {
        $this->king = $this->gameBoard->getKing($this->color);
        $alliesPos = $this->gameBoard->getAllChessmenByColor($this->color);
        $board = $this->gameBoard->getBoard();

        foreach ($alliesPos as $allyPos) {
            if (lcfirst($board[$allyPos['x']][$allyPos['y']]) === 'k') {
                continue;
            }

            $this->allies[] = $this->gameBoard->getChessman($allyPos);
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function validate(): bool
    {
        $king = $this->gameBoard->getKing($this->color);
        $dangerFromChessmen = $king->inSafety();

        if ($king->canMoveSomewhere()) {
            return true;
        }

        // If danger for the king doesn't exist it needs to check that at least one move exists for the player
        if (empty($dangerFromChessmen)) {
            return $this->anyMoveExist();
        }

        return $this->canSaveKingValidation($dangerFromChessmen);
    }

    /**
     * @return bool
     */
    private function anyMoveExist(): bool
    {
        foreach ($this->allies as $ally) {
            if ($ally->canMoveSomewhere()) {
                return true;
            }
        }

        return false;
    }

    /**
     * canSaveKingValidation can player make move that safe for him king.
     *
     * @param array $dangerFromChessmen
     * @return bool
     */
    private function canSaveKingValidation(array $dangerFromChessmen): bool
    {
        $board = $this->gameBoard->getBoard();

        foreach ($dangerFromChessmen as $dangerChessmanPos) {
            $dangerChessmanSymbol = lcfirst($board[$dangerChessmanPos['x']][$dangerChessmanPos['y']]);

            // These methods check if at least one chessman can save the king.
            $saveFrom = [
                    'b' => 'canSaveFromBishop',
                    'q' => 'canSaveFromQueen',
                    'r' => 'canSaveFromRook',
                ][$dangerChessmanSymbol] ?? 'canCaptureDanger';

            if ($this->$saveFrom($dangerChessmanPos) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $dangerChessmanPos
     * @return bool
     * @throws \Exception
     */
    private function canSaveFromQueen(array $dangerChessmanPos): bool
    {
        $kingPos = $this->king->getPosition();

        if ($this->gameBoard->getMainDiagonal($kingPos) === $this->gameBoard->getMainDiagonal($dangerChessmanPos)) {
            return $this->canSaveKingOnMainDiagonal($dangerChessmanPos);
        }

        if ($this->gameBoard->getAntiDiagonal($kingPos) === $this->gameBoard->getAntiDiagonal($dangerChessmanPos)) {
            return $this->canSaveKingOnAntiDiagonal($dangerChessmanPos);
        }

        if ($dangerChessmanPos['x'] === $kingPos['x']) {
            return $this->canSaveKingHorizontal($dangerChessmanPos);
        }

        if ($dangerChessmanPos['y'] === $kingPos['y']) {
            return $this->canSaveKingVertical($dangerChessmanPos);
        }

        return true;
    }

    /**
     * @param array $dangerChessmanPos
     * @return bool
     * @throws \Exception
     */
    private function canSaveFromRook(array $dangerChessmanPos): bool
    {
        $kingPos = $this->king->getPosition();

        if ($dangerChessmanPos['x'] === $kingPos['x']) {
            return $this->canSaveKingHorizontal($dangerChessmanPos);
        }

        if ($dangerChessmanPos['y'] === $kingPos['y']) {
            return $this->canSaveKingVertical($dangerChessmanPos);
        }

        return true;
    }

    /**
     * @param array $dangerChessmanPos
     * @return bool
     * @throws \Exception
     */
    private function canSaveFromBishop(array $dangerChessmanPos): bool
    {
        $kingPos = $this->king->getPosition();

        if ($this->gameBoard->getMainDiagonal($kingPos) === $this->gameBoard->getMainDiagonal($dangerChessmanPos)) {
            return $this->canSaveKingOnMainDiagonal($dangerChessmanPos);
        }

        if ($this->gameBoard->getAntiDiagonal($kingPos) === $this->gameBoard->getAntiDiagonal($dangerChessmanPos)) {
            return $this->canSaveKingOnAntiDiagonal($dangerChessmanPos);
        }

        return true;
    }

    /**
     * @param array $dangerChessmanPos
     * @return bool
     * @throws \Exception
     */
    private function canCaptureDanger(array $dangerChessmanPos): bool
    {
        foreach ($this->allies as $ally) {
            $moveInfo = $ally->moveValidation($dangerChessmanPos);

            if ($moveInfo->getStatus() && $this->safetyAfterMove($moveInfo)) {
                return true;
            }
        }

        return false;
    }

    /**
     * If any ally can block moves of danger chessman or capture it method return true.
     *
     * @param array $dangerChessmanPos
     * @return bool
     * @throws \Exception
     */
    private function canSaveKingHorizontal(array $dangerChessmanPos): bool
    {
        $kingPos = $this->king->getPosition();
        $dir = $kingPos['y'] < $dangerChessmanPos['y'] ? 1 : -1;
        $start = $kingPos['y'] + $dir;
        $end = $dangerChessmanPos['y'] + $dir;

        foreach ($this->allies as $ally) {
            for ($i = $start; $i !== $end; $i += $dir) {
                $moveInfo = $ally->moveValidation(['x' => $kingPos['x'], 'y' => $i]);

                if ($moveInfo->getStatus() && $this->safetyAfterMove($moveInfo)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * If any ally can block moves of danger chessman or capture it method return true.
     *
     * @param array $dangerChessmanPos
     * @return bool
     * @throws \Exception
     */
    private function canSaveKingVertical(array $dangerChessmanPos): bool
    {
        $kingPos = $this->king->getPosition();
        $dir = $kingPos['x'] < $dangerChessmanPos['x'] ? 1 : -1;
        $start = $kingPos['x'] + $dir;
        $end = $dangerChessmanPos['x'] + $dir;

        foreach ($this->allies as $ally) {
            for ($i = $start; $i !== $end; $i += $dir) {
                $moveInfo = $ally->moveValidation(['x' => $i, 'y' => $kingPos['y']]);

                if ($moveInfo->getStatus() && $this->safetyAfterMove($moveInfo)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * If any ally can block moves of danger chessman or capture it method return true.
     *
     * @param array $dangerChessmanPos
     * @return bool
     * @throws \Exception
     */
    private function canSaveKingOnMainDiagonal(array $dangerChessmanPos): bool
    {
        $kingPos = $this->king->getPosition();
        $dir = $kingPos['x'] < $dangerChessmanPos['x'] ? 1 : -1;
        $startX = $kingPos['x'] + $dir;
        $startY = $kingPos['y'] + $dir;
        $end = $dangerChessmanPos['x'] + $dir;

        foreach ($this->allies as $ally) {
            for ($x = $startX, $y = $startY; $x !== $end; $x += $dir, $y += $dir) {
                $moveInfo = $ally->moveValidation(['x' => $x, 'y' => $y]);

                if ($moveInfo->getStatus() && $this->safetyAfterMove($moveInfo)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * If any ally can block moves of danger chessman or capture it method return true.
     *
     * @param array $dangerChessmanPos
     * @return bool
     * @throws \Exception
     */
    private function canSaveKingOnAntiDiagonal(array $dangerChessmanPos): bool
    {
        $kingPos = $this->king->getPosition();

        $dir = $kingPos['x'] < $dangerChessmanPos['x'] ? 1 : -1;
        $startX = $kingPos['x'] + $dir;
        $startY = $kingPos['y'] - $dir;
        $end = $dangerChessmanPos['x'] + $dir;

        foreach ($this->allies as $ally) {
            for ($x = $startX, $y = $startY; $x !== $end; $x += $dir, $y -= $dir) {
                $moveInfo = $ally->moveValidation(['x' => $x, 'y' => $y]);

                if ($moveInfo->getStatus() && $this->safetyAfterMove($moveInfo)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param MoveInfo $moveInfo
     * @return bool
     * @throws \Exception
     */
    private function safetyAfterMove(MoveInfo $moveInfo): bool
    {
        $boardAfterSafeMove = $this->gameBoard->createAfterMove($moveInfo->getFrom(), $moveInfo->getTo());
        $kingAfterSafeMove = $boardAfterSafeMove->getKing($this->color);

        return empty($kingAfterSafeMove->inSafety());
    }
}
