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

    /**
     * @param array $pos
     * @return AbstractChessman
     */
    public function getChessman(array $pos): AbstractChessman
    {
        $chessman = lcfirst($this->board[$pos['x']][$pos['y']]);

        $color = $chessman === $this->board[$pos['x']][$pos['y']] ? 'black' : 'white';

        return (new $this->chessmen[$chessman]($pos, $color, $this));
    }
}
