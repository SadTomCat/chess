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
    private const CASTLING_AVAILABLE_DEFAULT = [
        'white' => [
            'k'  => true,
            'lr' => true,
            'rr' => true,
        ],
        'black' => [
            'k'  => true,
            'lr' => true,
            'rr' => true,
        ],
    ];

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
        ''  => NullChessman::class
    ];

    private array $lastMove = [];

    /**
     * k - king, lr - left rook, rr - right rook
     *
     * @var array|\bool[][]
     */
    private array $castlingAvailable = self::CASTLING_AVAILABLE_DEFAULT;

    /**
     * GameBoard factory.
     *
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
     * @param array $lastMove
     * @param array $castlingAvailable
     * @return null|GameBoard
     */
    public static function createByBoard(array $board, array $lastMove = [], array $castlingAvailable = []): ?GameBoard
    {
        $instance = new self();

        if ($instance->boardValid($board) === false) {
            return null;
        }

        $instance->board = $board;
        $instance->lastMove = $lastMove;
        $instance->castlingAvailable = empty($castlingAvailable) ? self::CASTLING_AVAILABLE_DEFAULT : $castlingAvailable;

        return $instance;
    }

    /**
     * @param array $from
     * @param array $to
     * @param array $lastMove
     * @param array $castlingAvailable
     * @return GameBoard|null
     */
    public function createAfterMove(
        array $from,
        array $to,
        array $lastMove = [],
        array $castlingAvailable = []
    ): ?GameBoard
    {
        $newBoard = $this->board;

        $newBoard[$to['x']][$to['y']] = $newBoard[$from['x']][$from['y']];
        $newBoard[$from['x']][$from['y']] = '';

        return static::createByBoard($newBoard, $lastMove, $castlingAvailable);
    }

    /**
     * @return array
     */
    public function getBoard(): array
    {
        return $this->board;
    }

    /**
     * @param string $color
     * @return array
     */
    public function getAllChessmenByColor(string $color): array
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
     * @param string $color
     * @return King
     * @throws Exception
     */
    public function getKing(string $color): King
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
     * @return MoveInfo
     */
    public function getLastMoveInfo(): MoveInfo
    {
        return (new MoveInfo(
            $this->lastMove['type'] ?? '',
            $this->lastMove['from'] ?? [],
            $this->lastMove['to'] ?? [],
        ));
    }

    /**
     * @return AbstractChessman
     */
    public function getLastMoveChessman(): AbstractChessman
    {
        return empty($this->lastMove['to'])
            ? (new NullChessman([], 'none', $this))
            : $this->getChessman(['x' => $this->lastMove['to']['x'], 'y' => $this->lastMove['to']['y']]);
    }

    /**
     * Check if player can make castling
     *
     * @param string $color
     * @param string $wing - wing for castling can be 'l' or 'r'
     * @return bool
     */
    public function isCastlingAvailable(string $color, string $wing): bool
    {
        return $this->castlingAvailable[$color][$wing . 'r'] === true && $this->castlingAvailable[$color]['k'] === true;
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
     * @param int $d
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
     * @param int $d
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
     * @return array
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
     * @param int $y
     * @param int $start
     * @param int $end
     * @param string $color
     * @return array
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
     * This method doesn't check the last diagonal because it got one cell.
     *
     * @param int $d
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
     * This method doesn't check the last diagonal because it got one cell.
     *
     * @param int $d
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
     * Recovery board to current condition
     *
     * @param Game $game
     */
    private function recovery(Game $game): void
    {
        foreach ($game->moves()->cursor() as $move) {
            switch ($move['type']) {
                case 'mate':
                case 'capture':
                case 'peace':
                    $this->move($move['from'], $move['to']);
                    break;
                case 'aisle':
                    $this->aisleMove($move['from'], $move['to']);
                    break;
                case 'castling':
                    $this->castlingMove($move['from'], $move['to']);
                    break;
                case 'promotion':
                    $this->promotionMove($move['from'], $move['to']);
                    break;
            }
        }

        $this->lastMove = $game->moves()->latest()->first(['from', 'to', 'type'])?->toArray() ?? [];
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
     * This method move chessman
     *
     * @param array $from
     * @param array $to
     */
    private function move(array $from, array $to): void
    {
        $fromChessmanInLower = lcfirst($this->board[$from['x']][$from['y']]);
        $color = $fromChessmanInLower === $this->board[$from['x']][$from['y']] ? 'black' : 'white';

        // Castling set
        switch ($fromChessmanInLower) {
            case 'k':
                $this->castlingAvailable[$color]['k'] = false;
                break;
            case 'r':
                if ($from['y'] === 0 || $from['y'] === 7) {
                    $dir = $from['y'] === 0 ? 'l' : 'r';
                    $this->castlingAvailable[$color][$dir . 'r'] = false;
                }
                break;
        }

        $this->board[$to['x']][$to['y']] = $this->board[$from['x']][$from['y']];
        $this->board[$from['x']][$from['y']] = '';
    }

    /**
     * @param array $from
     * @param array $to
     */
    private function aisleMove(array $from, array $to): void
    {
        $dir = $to['x'] > $from['x'] ? 1 : -1;

        $this->board[$to['x']][$to['y']] = $this->board[$from['x']][$from['y']];
        $this->board[$from['x']][$from['y']] = '';
        $this->board[$to['x'] - $dir][$to['y']] = '';
    }

    /**
     * @param array $from
     * @param array $to
     */
    private function castlingMove(array $from, array $to): void
    {
        $color = $from['x'] === 7 ? 'white' : 'black';

        if ($to['y'] === 6) /* short */ {
            $rook = $this->board[$from['x']][7];
            $this->board[$from['x']][7] = '';
            $dir = 1;
        } else  /* long */ {
            $rook = $this->board[$from['x']][0];
            $this->board[$from['x']][0] = '';
            $dir = -1;
        }

        // new king cell
        $this->board[$from['x']][$from['y'] + ($dir * 2)] = $this->board[$from['x']][$from['y']];
        $this->board[$from['x']][$from['y']] = '';

        // new rook cell
        $this->board[$from['x']][$from['y'] + $dir] = $rook;
        $this->castlingAvailable[$color]['k'] = false;
    }

    /**
     * @param array $from
     * @param array $to
     */
    private function promotionMove(array $from, array $to): void
    {
        $this->board[$to['x']][$to['y']] = $this->getColor($from) === 'white' ? 'Q' : 'q';
        $this->board[$from['x']][$from['y']] = '';
    }
}
