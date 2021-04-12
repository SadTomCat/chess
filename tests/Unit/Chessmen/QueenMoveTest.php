<?php

namespace Tests\Unit\Chessmen;

use App\Game\GameBoard;
use PHPUnit\Framework\TestCase;

class QueenMoveTest extends TestCase
{
    use HorizontalMoveTest, VerticalMoveTest, DiagonalMoveTest;

    protected string $chessman = 'Q';

    public function test_cannot(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', 'n', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'N', '', 'n', '', ''],
            ['', '', 'N', '', 'Q', 'n', '', ''],
            ['', '', '', 'N', '', 'n', '', ''],
            ['', '', '', '', 'N', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white  */
        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->validMoveByRule(['x' => 0, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 0, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 2, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 6, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 6, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 7, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 7, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 5, 'y' => 7]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 1, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());
    }
}
