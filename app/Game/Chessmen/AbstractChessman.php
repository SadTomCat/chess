<?php

namespace App\Game\Chessmen;

use App\Game\GameBoard;
use App\Game\MoveInfo;
use Exception;

abstract class AbstractChessman
{
    public const DANGEROUS_FOR_KING = 'King will not be safe';

    protected string $wrongMoveMessage = '';

    protected string $moveType = '';

    public function __construct(
        protected array $pos,
        protected string $color,
        protected GameBoard $board)
    {
    }

    /**
     * Rules validation
     *
     * @param array $to
     * @return MoveInfo
     */
    abstract public function validMoveByRule(array $to): MoveInfo;

    /**
     * @return bool
     */
    abstract public function canMoveSomewhere(): bool;

    /**
     * checks if the move conforms to the rules and will be safe for the king.
     *
     * @param array $to
     * @return MoveInfo
     */
    public function moveValidation(array $to): MoveInfo
    {
        $canMoveInfo = $this->validMoveByRule($to);

        if ($canMoveInfo->getStatus() === false) {
            return $canMoveInfo;
        }

        if ($this->willKingSafeAfterMove($to) === false) {
            return (new MoveInfo(status: false, message: self::DANGEROUS_FOR_KING));
        }

        return $this->createMoveInfo($to);
    }

    /**
     * @param array $kingPos
     * @return bool
     */
    public function safetyMoveOnHorizontal(array $kingPos): bool
    {
        if ($this->pos['x'] !== $kingPos['x']) {
            return true;
        }

        /*
         * -1 - left
         * 1 - right
         * */
        $dir = $kingPos['y'] > $this->pos['y'] ? -1 : 1;

        if (($dir === -1 && $this->pos['y'] === 0) || ($dir === 1 && $this->pos['y'] === 7)) {
            return true;
        }

        $end = $dir === 1 ? 7 : 0;

        $betweenKingAndOwn = abs($kingPos['y'] - $this->pos['y']) === 1 ?
            []
            : $this->board->getFirstOnHorizontal($kingPos['x'], $this->pos['y'] - $dir, $kingPos['y'] + $dir);

        if (empty($betweenKingAndOwn) === false) {
            return true;
        }

        $firstOnHorPos = $this->board->getFirstOnHorizontal($kingPos['x'], $this->pos['y'] + $dir, $end);

        if (empty($firstOnHorPos)) {
            return true;
        }

        $board = $this->board->getBoard();
        $chessmanSymbol = lcfirst($board[$firstOnHorPos['x']][$firstOnHorPos['y']]);

        if ($chessmanSymbol !== 'r' && $chessmanSymbol !== 'q') {
            return true;
        }

        $firstOnHorColor = $this->board->getColor($firstOnHorPos);

        return $firstOnHorColor === $this->color;
    }

    /**
     * @param array $kingPos
     * @return bool
     */
    public function safetyMoveOnVertical(array $kingPos): bool
    {
        if ($this->pos['y'] !== $kingPos['y']) {
            return true;
        }

        /*
         * -1 - top
         * 1 - bottom
         * */
        $dir = $kingPos['x'] > $this->pos['x'] ? -1 : 1;

        if (($dir === -1 && $this->pos['x'] === 0) || ($dir === 1 && $this->pos['x'] === 7)) {
            return true;
        }

        $end = $dir === 1 ? 7 : 0;

        $betweenKingAndOwn = abs($kingPos['x'] - $this->pos['x']) === 1 ?
            []
            : $this->board->getFirstOnVertical($kingPos['y'], $this->pos['x'] - $dir, $kingPos['x'] + $dir);

        if (empty($betweenKingAndOwn) === false) {
            return true;
        }

        $firstOnVerPos = $this->board->getFirstOnVertical($kingPos['y'], $this->pos['x'] + $dir, $end);

        if (empty($firstOnVerPos)) {
            return true;
        }

        $board = $this->board->getBoard();
        $chessmanSymbol = lcfirst($board[$firstOnVerPos['x']][$firstOnVerPos['y']]);

        if ($chessmanSymbol !== 'r' && $chessmanSymbol !== 'q') {
            return true;
        }

        $firstOnHorColor = $this->board->getColor($firstOnVerPos);
        return $firstOnHorColor === $this->color;
    }

    /**
     * @param array $kingPos
     * @return bool
     * @throws Exception
     */
    public function safetyMoveOnMainDiagonal(array $kingPos): bool
    {
        $ownDiagonal = $this->board->getMainDiagonal($this->pos);
        if ($this->board->getMainDiagonal($kingPos) !== $ownDiagonal || abs($ownDiagonal) >= 6) {
            return true;
        }

        /*
         * -1 - top K Q
         * 1 - bottom Q K
         * */
        $dir = $kingPos['x'] > $this->pos['x'] ? -1 : 1;

        if (($ownDiagonal >= 0 && (($dir === -1 && $this->pos['x'] === 0) || ($dir === 1 && $this->pos['y'] === 7)))
            || ($ownDiagonal < 0 && (($dir === -1 && $this->pos['y'] === 0) || ($dir === 1 && $this->pos['x'] === 7)))) {
            return true;
        }

        $betweenKingAndOwn = abs($kingPos['x'] - $this->pos['x']) === 1 ?
            []
            : $this->board->getFirstOnDiagonal(
                startPos: ['x' => $this->pos['x'] - $dir, 'y' => $this->pos['y'] - $dir],
                endPos: ['x' => $kingPos['x'] + $dir, 'y' => $kingPos['y'] + $dir]
            );

        if (empty($betweenKingAndOwn) === false) {
            return true;
        }

        /*
         * -1 - top K Q
         * 1 - bottom Q K
         * */
        $endPos = $dir === -1 ?
            /* top */
            [
                'x' => $ownDiagonal >= 0 ? 0 : abs($ownDiagonal),
                'y' => $ownDiagonal > 0 ? abs($ownDiagonal) : 0,
            ]
            /* bottom */
            : [
                'x' => $ownDiagonal > 0 ? 7 - abs($ownDiagonal) : 7,
                'y' => $ownDiagonal >= 0 ? 7 : 7 - abs($ownDiagonal),
            ];

        $nextPos = ['x' => $this->pos['x'] + $dir, 'y' => $this->pos['y'] + $dir];

        $firstOnDiagonalPos = $this->board->getFirstOnDiagonal(startPos: $nextPos, endPos: $endPos);

        if (empty($firstOnDiagonalPos)) {
            return true;
        }

        $board = $this->board->getBoard();
        $chessmanSymbol = lcfirst($board[$firstOnDiagonalPos['x']][$firstOnDiagonalPos['y']]);

        if ($chessmanSymbol !== 'b' && $chessmanSymbol !== 'q') {
            return true;
        }

        $firstOnHorColor = $this->board->getColor($firstOnDiagonalPos);

        return $firstOnHorColor === $this->color;
    }

    /**
     * @param array $kingPos
     * @return bool
     * @throws Exception
     */
    public function safetyMoveOnAntiDiagonal(array $kingPos): bool
    {
        $ownDiagonal = $this->board->getAntiDiagonal($this->pos);
        if ($this->board->getAntiDiagonal($kingPos) !== $ownDiagonal || abs($ownDiagonal) >= 6) {
            return true;
        }

        /*
         * -1 - left  Q K
         * 1 - right K Q
         * */
        $dir = $kingPos['y'] > $this->pos['y'] ? -1 : 1;

        if (($ownDiagonal >= 0 && (($dir === -1 && $this->pos['y'] === 0) || ($dir === 1 && $this->pos['x'] === 0)))
            || ($ownDiagonal < 0 && (($dir === -1 && $this->pos['x'] === 7) || ($dir === 1 && $this->pos['y'] === 7)))) {
            return true;
        }

        $betweenKingAndOwn = abs($kingPos['x'] - $this->pos['x']) === 1 ?
            []
            : $this->board->getFirstOnAntiDiagonal(
                startPos: ['x' => $this->pos['x'] + $dir, 'y' => $this->pos['y'] - $dir],
                endPos: ['x' => $kingPos['x'] - $dir, 'y' => $kingPos['y'] + $dir]
            );

        if (empty($betweenKingAndOwn) === false) {
            return true;
        }

        /*
         * -1 - left  Q K
         * 1 - right K Q
         * */
        $endPos = $dir === -1 ?
            /* left */
            [
                'x' => $ownDiagonal > 0 ? 7 - $ownDiagonal : 7,
                'y' => $ownDiagonal >= 0 ? 0 : abs($ownDiagonal),
            ]
            /* right */
            : [
                'x' => $ownDiagonal >= 0 ? 0 : abs($ownDiagonal),
                'y' => $ownDiagonal >= 0 ? 7 - abs($ownDiagonal) : 7,
            ];

        $nextPos = ['x' => $this->pos['x'] - $dir, 'y' => $this->pos['y'] + $dir];
        $firstOnDiagonalPos = $this->board->getFirstOnAntiDiagonal(startPos: $nextPos, endPos: $endPos);

        if (empty($firstOnDiagonalPos)) {
            return true;
        }

        $board = $this->board->getBoard();
        $chessmanSymbol = lcfirst($board[$firstOnDiagonalPos['x']][$firstOnDiagonalPos['y']]);

        if ($chessmanSymbol !== 'b' && $chessmanSymbol !== 'q') {
            return true;
        }

        $firstOnHorColor = $this->board->getColor($firstOnDiagonalPos);

        return $firstOnHorColor === $this->color;
    }

    /**
     * @param array $to
     * @return bool
     */
    public function willKingSafeAfterMove(array $to): bool
    {
        try {
            $kingPos = $this->board->getKing($this->color)->getPosition();

            if ($kingPos['x'] === $this->pos['x'] && $this->pos['x'] !== $to['x']) {
                return $this->safetyMoveOnHorizontal($kingPos);
            }

            if ($kingPos['y'] === $this->pos['y'] && $this->pos['y'] !== $to['y']) {
                return $this->safetyMoveOnVertical($kingPos);
            }

            $ownDiagonal = $this->board->getMainDiagonal($this->pos);

            if ($this->board->getMainDiagonal($kingPos) === $ownDiagonal
                && $ownDiagonal !== $this->board->getMainDiagonal($to)) {
                return $this->safetyMoveOnMainDiagonal($kingPos);
            }

            $ownAntiDiagonal = $this->board->getAntiDiagonal($this->pos);

            if ($this->board->getAntiDiagonal($kingPos) === $ownAntiDiagonal
                && $ownAntiDiagonal !== $this->board->getAntiDiagonal($to)) {
                return $this->safetyMoveOnAntiDiagonal($kingPos);
            }

        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @return array
     */
    public function getPosition(): array
    {
        return $this->pos;
    }

    /**
     * @param array $to
     * @return bool
     */
    protected function canDiagonal(array $to): bool
    {
        $difX = $this->pos['x'] - $to['x'];
        $difY = $this->pos['y'] - $to['y'];

        if (abs($difX) !== abs($difY)) {
            $this->wrongMoveMessage = 'You can move only on diagonal';
            return false;
        }

        if (!$this->checkToPos($to)) {
            return false;
        }

        $dirX = $difX > 0 ? -1 : 1;
        $dirY = $difY > 0 ? -1 : 1;
        $nextX = $this->pos['x'];
        $nextY = $this->pos['y'];
        $board = $this->board->getBoard();

        for ($i = 0; $i < abs($difX) - 1; $i++) {
            $nextX += $dirX;
            $nextY += $dirY;

            if ($board[$nextX][$nextY] !== '') {
                $this->wrongMoveMessage = 'On the way met chessman';
                return false;
            }
        }

        return true;
    }

    /**
     * @param $to
     * @return bool
     */
    protected function canHorizontal(array $to): bool
    {
        $difY = $this->pos['y'] - $to['y'];
        $dirY = $difY > 0 ? -1 : 1;

        if ($this->pos['x'] !== $to['x']) {
            $this->wrongMoveMessage = 'You can move only on horizontal';
            return false;
        }

        if (!$this->checkToPos($to)) {
            return false;
        }

        $nextY = $this->pos['y'];
        $board = $this->board->getBoard();

        for ($i = 0; $i < abs($difY) - 1; $i++) {
            $nextY += $dirY;

            if ($board[$to['x']][$nextY] !== '') {
                $this->wrongMoveMessage = 'On the way met chessman';
                return false;
            }
        }

        return true;
    }

    /**
     * @param $to
     * @return bool
     */
    protected function canVertically(array $to): bool
    {
        $difX = $this->pos['x'] - $to['x'];
        $dirX = $difX > 0 ? -1 : 1;

        if ($this->pos['y'] !== $to['y']) {
            $this->wrongMoveMessage = 'You can move only on vertically';
            return false;
        }

        if (!$this->checkToPos($to)) {
            return false;
        }

        $nextX = $this->pos['x'];
        $board = $this->board->getBoard();

        for ($i = 0; $i < abs($difX) - 1; $i++) {
            $nextX += $dirX;

            if ($board[$nextX][$to['y']] !== '') {
                $this->wrongMoveMessage = 'On the way met chessman';
                return false;
            }
        }

        return true;
    }

    /**
     * Colors of chessman in start position and chessman in 'to' position must be different
     * or 'to' position are empty
     *
     * @param $to
     * @return bool
     */
    protected function checkToPos(array $to): bool
    {
        if ($this->board->getChessman($to)->color !== $this->color) {
            return true;
        }

        $this->wrongMoveMessage = 'You are trying capture own chessman';
        return false;
    }

    /**
     * @param array $to
     * @return string
     */
    protected function defineType(array $to): string
    {
        if ($this->moveType !== '') {
            return $this->moveType;
        }

        if ($this->board->getChessman($to) instanceof NullChessman) {
            return 'peace';
        }

        return 'capture';
    }

    /**
     * @param array $to
     * @param bool $status
     * @param string $message
     * @return MoveInfo
     */
    protected function createMoveInfo(array $to, bool $status = true, string $message = ''): MoveInfo
    {
        if ($status === true) {
            return (new MoveInfo($this->defineType($to), $this->pos, $to));
        }

        return (new MoveInfo(
            status: false,
            message: $message === '' ? $this->wrongMoveMessage : $message
        ));
    }

    /**
     * @param array $moves
     * @return bool
     */
    protected function canAnyByMoves(array $moves): bool
    {
        foreach ($moves as $move) {
            if ($move['x'] === 8 || $move['y'] === 8 || $move['x'] === -1 || $move['y'] === -1) {
                continue;
            }

            if ($this->moveValidation($move)->getStatus()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function anyOnHorizontal(): bool
    {
        $x = $this->pos['x'];

        for ($i = 0; $i < 8; $i++) {
            if ($this->moveValidation(['x' => $x, 'y' => $i])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function anyOnVertical(): bool
    {
        $y = $this->pos['y'];

        for ($i = 0; $i < 8; $i++) {
            if ($this->moveValidation(['x' => $i, 'y' => $y])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function anyOnMainDiagonal(): bool
    {
        $diagonal = $this->board->getMainDiagonal($this->pos);
        $startX = $diagonal >= 0 ? 0 : abs($diagonal);
        $startY = $diagonal > 0 ? $diagonal : 0;
        $end = 8 - abs($diagonal);

        for ($x = $startX, $y = $startY; $x !== $end; $x++, $y++) {
            if ($this->moveValidation(['x' => $x, 'y' => $y])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function anyOnAntiDiagonal(): bool
    {
        $diagonal = $this->board->getMainDiagonal($this->pos);
        $startX = $diagonal >= 0 ? 0 : abs($diagonal);
        $startY = $diagonal > 0 ? 7 - $diagonal : 7;
        $end = abs($diagonal);

        for ($x = $startX, $y = $startY; $x !== $end; $x++, $y--) {
            if ($this->moveValidation(['x' => $x, 'y' => $y])) {
                return true;
            }
        }

        return false;
    }
}
