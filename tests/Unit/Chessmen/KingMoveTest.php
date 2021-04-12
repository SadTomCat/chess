<?php

namespace Tests\Unit\Chessmen;

use App\Game\GameBoard;
use PHPUnit\Framework\TestCase;

class KingMoveTest extends TestCase
{
    public function test_peace_move_true(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', 'k', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', 'K', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white  */
        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 4]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 5]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 5]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 5, 'y' => 5]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 5, 'y' => 4]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 5, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        /* black  */
        $chessman = $board->getChessman(['x' => 2, 'y' => 2]);

        $moveInfo = $chessman->validMoveByRule(['x' => 1, 'y' => 1]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 1, 'y' => 2]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 1, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 2, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 2]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 1]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 2, 'y' => 1]);
        $this->assertTrue($moveInfo->getStatus());
    }

    public function test_peace_move_false(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', 'k', 'q', '', '', '', ''],
            ['', 'Q', 'q', 'Q', 'q', 'Q', '', ''],
            ['', '', '', 'Q', 'K', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white  */
        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 2, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 5]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 6, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 6, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 5, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        /* black  */
        $chessman = $board->getChessman(['x' => 2, 'y' => 2]);

        $moveInfo = $chessman->validMoveByRule(['x' => 0, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 0, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 0, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 2, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 2, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());
    }

    public function test_capture_move_true(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', 'Q', 'Q', 'Q', '', '', '', ''],
            ['', 'Q', 'k', 'Q', '', '', '', ''],
            ['', 'Q', 'Q', 'Q', '', '', '', ''],
            ['', '', '', '', 'q', 'q', 'q', ''],
            ['', '', '', '', 'q', 'K', 'q', ''],
            ['', '', '', '', 'q', 'q', 'q', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white  */
        $chessman = $board->getChessman(['x' => 5, 'y' => 5]);

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 4]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 5]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 6]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 5, 'y' => 6]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 6, 'y' => 6]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 6, 'y' => 5]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 6, 'y' => 4]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 5, 'y' => 4]);
        $this->assertTrue($moveInfo->getStatus());

        /* black  */
        $chessman = $board->getChessman(['x' => 2, 'y' => 2]);

        $moveInfo = $chessman->validMoveByRule(['x' => 1, 'y' => 1]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 1, 'y' => 2]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 1, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 2, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 2]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 1]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 2, 'y' => 1]);
        $this->assertTrue($moveInfo->getStatus());
    }

    public function test_capture_move_false(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', 'Q', '', '', '', '', ''],
            ['', 'q', 'q', 'q', '', '', '', ''],
            ['', 'q', 'k', 'q', '', '', '', ''],
            ['', 'q', 'q', 'q', '', '', '', ''],
            ['', '', '', '', 'Q', 'Q', 'Q', ''],
            ['', '', '', 'q', 'Q', 'K', 'Q', ''],
            ['', '', '', '', 'Q', 'Q', 'Q', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white  */
        $chessman = $board->getChessman(['x' => 5, 'y' => 5]);

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 5]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 5, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 6, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 6, 'y' => 5]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 6, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 5, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        /* black  */
        $chessman = $board->getChessman(['x' => 2, 'y' => 2]);

        $moveInfo = $chessman->validMoveByRule(['x' => 1, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 0, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 1, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 2, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 2, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());
    }

    public function test_cannot(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', 'n', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'N', '', 'n', '', ''],
            ['', '', 'N', '', 'K', 'n', '', ''],
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
