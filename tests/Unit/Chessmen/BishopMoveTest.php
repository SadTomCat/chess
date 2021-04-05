<?php

namespace Tests\Unit\Chessmen;

use App\Game\GameBoard;
use PHPUnit\Framework\TestCase;

class BishopMoveTest extends TestCase
{
    use DiagonalMoveTest;

    private string $chessman = 'B';

    public function test_cannot(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', 'q', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', 'q', '', '', '', 'q', ''],
            ['', '', '', '', 'B', '', '', ''],
            ['', '', '', '', '', '', 'q', ''],
            ['', '', '', '', 'q', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white  */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 1, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 1, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', 'q', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', 'q', '', '', '', 'q', ''],
            ['', '', '', '', 'b', '', '', ''],
            ['', '', '', '', '', '', 'q', ''],
            ['', '', '', '', 'q', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* black  */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 1, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 1, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());
    }
}
