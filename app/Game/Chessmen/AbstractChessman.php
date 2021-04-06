<?php

namespace App\Game\Chessmen;

use App\Game\GameBoard;
use App\Game\MoveInfo;

abstract class AbstractChessman
{
    protected string $wrongMoveMessage = '';

    public function __construct(
        protected array $pos,
        protected string $color,
        protected GameBoard $board)
    {
    }

    /**
     * @param object $to
     * @return bool
     */
    abstract public function canMove(array $to): MoveInfo;

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
        if ($this->board->getChessman($to) instanceof NullChessman) {
            return 'peace';
        }

        return 'capture';
    }

    protected function createMoveInfo(array $to, $status = true, string $message = ''): MoveInfo
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
}
