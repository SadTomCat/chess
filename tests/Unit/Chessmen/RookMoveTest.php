<?php

namespace Tests\Unit\Chessmen;

use App\Game\GameBoard;
use PHPUnit\Framework\TestCase;

class RookMoveTest extends TestCase
{
    use VerticalMoveTest, HorizontalMoveTest;

    private string $chessman = 'R';

    public function test_cannot(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', 'q', '', ''],
            ['', '', '', '', 'R', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', 'q'],
        ]);

        /* white  */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);
        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 5]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 5]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 1, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 7]);
        $this->assertFalse($moveInfo->getStatus());

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', 'q', '', ''],
            ['', '', '', '', 'r', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', 'q'],
        ]);

        /* black  */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);
        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 5]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 5]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 1, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 7]);
        $this->assertFalse($moveInfo->getStatus());
    }
}
