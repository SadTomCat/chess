<?php

namespace App\Game;

use App\Game\Chessmen\AbstractChessman;
use App\Game\Chessmen\Bishop;
use App\Game\Chessmen\King;
use App\Game\Chessmen\Knight;
use App\Game\Chessmen\NullChessman;
use App\Game\Chessmen\Pawn;
use App\Game\Chessmen\Queen;
use App\Game\Chessmen\Rook;
use App\Models\Game;
use Exception;

class GameBoard
{
    private array $board = [
        ['r', 'n', 'b', 'q', 'k', 'b', 'n', 'r'],
        ['p', 'p', 'p', 'p', 'p', 'p', 'p', 'p'],
        ['', '', '', '', '', '', '', ''],
        ['', '', '', '', '', '', '', ''],
        ['', '', '', '', '', '', '', ''],
        ['', '', '', '', '', '', '', ''],
        ['P', 'P', 'P', 'P', 'P', 'P', 'P', 'P'],
        ['R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R'],
    ];

    private array $chessmen = [
        'p' => Pawn::class,
        'k' => King::class,
        'q' => Queen::class,
        'r' => Rook::class,
        'b' => Bishop::class,
        'n' => Knight::class,
        '' => NullChessman::class
    ];

    /**
     * GameBoard factory.
     * @param Game $game
     * @return GameBoard
     */
    public static function createByGame(Game $game): GameBoard
    {
        $instance = new self();
        $instance->recovery($game);

        return $instance;
    }

    /**
     * GameBoard factory.
     *
     * @param array $board Should be  8 - x and 8 - y
     * @return null|GameBoard
     */
    public static function createByBoard(array $board): ?GameBoard
    {
        $instance = new self();

        if ($instance->boardValid($board) === false) {
            return null;
        }

        $instance->board = $board;

        return $instance;
    }

    /**
     * @param array $board
     * @param array $from
     * @param $to
     * @return GameBoard|null
     */
    public function createAfterMove(array $from, $to): ?GameBoard
    {
        $newBoard = $this->board;

        $newBoard[$to['x']][$to['y']] = $newBoard[$from['x']][$from['y']];
        $newBoard[$from['x']][$from['y']] = '';

        return static::createByBoard($newBoard);
    }

    public function getAllChessmenByColor($color): array
    {
        $chessmen = [];

        for ($x = 0; $x < 8; $x++) {
            for ($y = 0; $y < 8; $y++) {
                $currentChessmanColor = $this->getColor(['x' => $x, 'y' => $y]);

                if ($currentChessmanColor === $color) {
                    $chessmen[] = ['x' => $x, 'y' => $y];
                }
            }
        }

        return $chessmen;
    }

    /**
     * @param array $pos
     * @return string
     */
    public function getColor(array $pos): string
    {
        $chessman = $this->board[$pos['x']][$pos['y']];

        if ($chessman === '') {
            return 'none';
        }

        return $chessman === ucfirst($chessman) ? 'white' : 'black';
    }

    /**
     * @param array $pos
     * @return AbstractChessman
     */
    public function getChessman(array $pos): AbstractChessman
    {
        $chessman = lcfirst($this->board[$pos['x']][$pos['y']]);

        /* Don't use getColor function */
        $color = $chessman === $this->board[$pos['x']][$pos['y']] ? 'black' : 'white';

        return (new $this->chessmen[$chessman]($pos, $color, $this));
    }

    /**
     * @param $color
     * @return King
     * @throws Exception
     */
    public function getKing($color): King
    {
        $need = $color === 'white' ? 'K' : 'k';

        for ($x = 0; $x < 8; $x++) {
            for ($y = 0; $y < 8; $y++) {
                if ($this->board[$x][$y] === $need) {
                    return new King(['x' => $x, 'y' => $y], $color, $this);
                }
            }
        }

        throw (new Exception('King isn\'t exist'));
    }

    /**
     * @param array $pos
     * @return int
     */
    public function getMainDiagonal(array $pos = []): int
    {
        return $pos['y'] - $pos['x'];
    }

    /**
     * @param array $pos
     * @return int
     */
    public function getAntiDiagonal(array $pos = []): int
    {
        return abs($pos['y'] - 7) - $pos['x'];
    }

    /**
     * @param string $color
     * @param int $x
     * @param int $start
     * @param int $end
     * @return array
     */
    public function getAllOnHorizontal(int $x, int $start = 0, int $end = 7, string $color = ''): array
    {
        $chessmen = [];
        $horizontal = $this->board[$x];
        $dir = $start < $end ? 1 : -1;
        $end += $dir;

        for ($i = $start; $i !== $end; $i += $dir) {
            if ($horizontal[$i] === '') {
                continue;
            }

            if ($color === '') {
                $chessmen[] = ['x' => $x, 'y' => $i];
            } else if ($color === $this->getColor(['x' => $x, 'y' => $i])) {
                $chessmen[] = ['x' => $x, 'y' => $i];
            }
        }

        return $chessmen;
    }

    /**
     * @param string $color
     * @param int $y
     * @param int $start
     * @param int $end
     * @return array
     */
    public function getAllOnVertical(int $y, int $start = 0, int $end = 7, string $color = ''): array
    {
        $chessmen = [];
        $dir = $start < $end ? 1 : -1;
        $end += $dir;

        for ($i = $start; $i !== $end; $i += $dir) {
            if ($this->board[$i][$y] === '') {
                continue;
            }

            if ($color === '') {
                $chessmen[] = ['x' => $i, 'y' => $y];

            } else if ($color === $this->getColor(['x' => $i, 'y' => $y])) {
                $chessmen[] = ['x' => $i, 'y' => $y];
            }
        }

        return $chessmen;
    }

    /**
     * This method dont check last diagonal because it got one cell
     *
     * @param array $startPos
     * @param array $endPos
     * @param string $color
     * @return array
     * @throws Exception
     */
    public function getAllOnDiagonal(int $d = 0, array $startPos = [], array $endPos = [], string $color = ''): array
    {
        if ($d === 0) {
            $d = empty($endPos) && empty($startPos) === false ? $this->getMainDiagonal($startPos) : $d;
            $d = empty($startPos) && empty($endPos) === false ? $this->getMainDiagonal($endPos) : $d;
        }

        $dStartX = $d < 0 ? abs($d) : 0;
        $dStartY = $d > 0 ? $d : 0;
        $dEndX = 7 - $dStartY;
        $dEndY = 7 - $dStartX;

        $startPos = empty($startPos) ? ['x' => $dStartX, 'y' => $dStartY] : $startPos;
        $endPos = empty($endPos) ? ['x' => $dEndX, 'y' => $dEndY] : $endPos;

        if ($this->getMainDiagonal($startPos) !== $this->getMainDiagonal($endPos)) {
            throw (new Exception('Not same diagonal'));
        }

        $chessmen = [];
        $dir = $startPos['x'] < $endPos['x'] ? 1 : -1;
        $end = $endPos['x'] + $dir;

        for ($x = $startPos['x'], $y = $startPos['y']; $x !== $end; $x += $dir, $y += $dir) {
            if ($this->board[$x][$y] === '') {
                continue;
            }

            if ($color === '') {
                $chessmen[] = ['x' => $x, 'y' => $y];

            } else if ($color === $this->getColor(['x' => $x, 'y' => $y])) {
                $chessmen[] = ['x' => $x, 'y' => $y];
            }
        }

        return $chessmen;
    }

    /**
     * This method dont check last diagonal because it got one cell
     *
     * @param array $startPos
     * @param array $endPos
     * @param string $color
     * @return array
     * @throws Exception
     */
    public function getAllOnAntiDiagonal(int $d = 0, array $startPos = [], array $endPos = [], string $color = ''): array
    {
        if ($d === 0) {
            $d = empty($endPos) && empty($startPos) === false ? $this->getAntiDiagonal($startPos) : $d;
            $d = empty($startPos) && empty($endPos) === false ? $this->getAntiDiagonal($endPos) : $d;
        }

        $dStartX = $d < 0 ? abs($d) : 0;
        $dStartY = $d >= 0 ? abs($d - 7) : 7;
        $dEndX = $dStartY;
        $dEndY = $dStartX;

        $startPos = empty($startPos) ? ['x' => $dStartX, 'y' => $dStartY] : $startPos;
        $endPos = empty($endPos) ? ['x' => $dEndX, 'y' => $dEndY] : $endPos;

        if ($this->getAntiDiagonal($startPos) !== $this->getAntiDiagonal($endPos)) {
            throw (new Exception('Not same anti diagonal'));
        }

        $chessmen = [];
        $dir = $startPos['x'] < $endPos['x'] ? 1 : -1;
        $end = $endPos['x'] + $dir;

        for ($x = $startPos['x'], $y = $startPos['y']; $x !== $end; $x += $dir, $y -= $dir) {
            if ($this->board[$x][$y] === '') {
                continue;
            }

            if ($color === '') {
                $chessmen[] = ['x' => $x, 'y' => $y];

            } else if ($color === $this->getColor(['x' => $x, 'y' => $y])) {
                $chessmen[] = ['x' => $x, 'y' => $y];
            }
        }

        return $chessmen;
    }

    /**
     * @param int $x
     * @param int $start
     * @param int $end
     * @param string $color
     * @return array|int[]
     */
    public function getFirstOnHorizontal(int $x, int $start = 0, int $end = 7, string $color = ''): array
    {
        $horizontal = $this->board[$x];
        $dir = $start < $end ? 1 : -1;
        $end += $dir;

        for ($i = $start; $i !== $end; $i += $dir) {
            if ($horizontal[$i] === '') {
                continue;
            }

            if ($color === '') {
                return ['x' => $x, 'y' => $i];
            }

            if ($this->getColor(['x' => $x, 'y' => $i]) === $color) {
                return ['x' => $x, 'y' => $i];
            }
        }

        return [];
    }

    /**
     * @param int $x
     * @param int $start
     * @param int $end
     * @param string $color
     * @return array|int[]
     */
    public function getFirstOnVertical(int $y, int $start = 0, int $end = 7, string $color = ''): array
    {
        $dir = $start < $end ? 1 : -1;
        $end += $dir;

        for ($i = $start; $i !== $end; $i += $dir) {
            if ($this->board[$i][$y] === '') {
                continue;
            }

            if ($color === '') {
                return ['x' => $i, 'y' => $y];
            }

            if ($this->getColor(['x' => $i, 'y' => $y]) === $color) {
                return ['x' => $i, 'y' => $y];
            }
        }

        return [];
    }

    /**
     * This method dont check last diagonal because it got one cell
     *
     * @param array $startPos
     * @param array $endPos
     * @param string $color
     * @return array
     * @throws Exception
     */
    public function getFirstOnDiagonal(int $d = 0, array $startPos = [], array $endPos = [], string $color = ''): array
    {
        if ($d === 0) {
            $d = empty($endPos) && empty($startPos) === false ? $this->getMainDiagonal($startPos) : $d;
            $d = empty($startPos) && empty($endPos) === false ? $this->getMainDiagonal($endPos) : $d;
        }

        $dStartX = $d < 0 ? abs($d) : 0;
        $dStartY = $d > 0 ? $d : 0;
        $dEndX = 7 - $dStartY;
        $dEndY = 7 - $dStartX;

        $startPos = empty($startPos) ? ['x' => $dStartX, 'y' => $dStartY] : $startPos;
        $endPos = empty($endPos) ? ['x' => $dEndX, 'y' => $dEndY] : $endPos;

        if ($this->getMainDiagonal($startPos) !== $this->getMainDiagonal($endPos)) {
            throw (new Exception('Not same diagonal'));
        }

        $dir = $startPos['x'] < $endPos['x'] ? 1 : -1;
        $end = $endPos['x'] + $dir;

        for ($x = $startPos['x'], $y = $startPos['y']; $x !== $end; $x += $dir, $y += $dir) {
            if ($this->board[$x][$y] === '') {
                continue;
            }

            if ($color === '') {
                return ['x' => $x, 'y' => $y];

            } else if ($color === $this->getColor(['x' => $x, 'y' => $y])) {
                return ['x' => $x, 'y' => $y];
            }
        }

        return [];
    }

    /**
     * This method dont check last diagonal because it got one cell
     *
     * @param array $startPos
     * @param array $endPos
     * @param string $color
     * @return array
     * @throws Exception
     */
    public function getFirstOnAntiDiagonal(int $d = 0, array $startPos = [], array $endPos = [], string $color = ''): array
    {
        if ($d === 0) {
            $d = empty($endPos) && empty($startPos) === false ? $this->getAntiDiagonal($startPos) : $d;
            $d = empty($startPos) && empty($endPos) === false ? $this->getAntiDiagonal($endPos) : $d;
        }

        $dStartX = $d < 0 ? abs($d) : 0;
        $dStartY = $d >= 0 ? abs($d - 7) : 7;
        $dEndX = $dStartY;
        $dEndY = $dStartX;

        $startPos = empty($startPos) ? ['x' => $dStartX, 'y' => $dStartY] : $startPos;
        $endPos = empty($endPos) ? ['x' => $dEndX, 'y' => $dEndY] : $endPos;

        if ($this->getAntiDiagonal($startPos) !== $this->getAntiDiagonal($endPos)) {
            throw (new Exception('Not same anti diagonal'));
        }

        $chessmen = [];
        $dir = $startPos['x'] < $endPos['x'] ? 1 : -1;
        $end = $endPos['x'] + $dir;

        for ($x = $startPos['x'], $y = $startPos['y']; $x !== $end; $x += $dir, $y -= $dir) {
            if ($this->board[$x][$y] === '') {
                continue;
            }

            if ($color === '') {
                return ['x' => $x, 'y' => $y];

            } else if ($color === $this->getColor(['x' => $x, 'y' => $y])) {
                return ['x' => $x, 'y' => $y];
            }
        }

        return $chessmen;
    }

    /**
     * @param Game $game
     */
    private function recovery(Game $game): void
    {
        foreach ($game->moves()->cursor() as $move) {
            switch ($move['type']) {
                case 'capture':
                case 'peace':
                    $this->move($move['from'], $move['to']);
                    break;
            }
        }
    }

    /**
     * @param array $board
     * @return bool
     */
    private function boardValid(array $board): bool
    {
        if (count($board) !== 8) {
            return false;
        }

        foreach ($board as $x) {
            if (count($x) !== 8) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function getBoard(): array
    {
        return $this->board;
    }

    /**
     * This method move chessman
     *
     * @param object $from
     * @param object $to
     */
    private function move(array $from, array $to): void
    {
        $this->board[$to['x']][$to['y']] = $this->board[$from['x']][$from['y']];
        $this->board[$from['x']][$from['y']] = '';
    }
}
