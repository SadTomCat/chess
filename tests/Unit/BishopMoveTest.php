<?php

namespace Tests\Unit;

use App\Game\GameBoard;
use PHPUnit\Framework\TestCase;

class BishopMoveTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_diagonal_peace_true(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'b', 'B', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 0, 'y' => 0]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 5]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 6]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 1]);
        $this->assertTrue($moveInfo->getStatus());

        /* black */

        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);

        $moveInfo = $chessman->canMove(['x' => 1, 'y' => 0]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 5]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 4]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 1]);
        $this->assertTrue($moveInfo->getStatus());
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_diagonal_peace_false(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', 'Q', '', '', '', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', 'q', '', '', 'q', '', ''],
            ['', '', '', 'b', 'B', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', 'Q', '', '', '', 'q', 'Q', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 0, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 0, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        /* black */

        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 1, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_diagonal_capture_true(): void
    {
        $board = GameBoard::createByBoard([
            ['q', '', '', '', '', '', '', 'Q'],
            ['Q', '', '', '', '', '', '', 'q'],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'b', 'B', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['Q', 'q', '', '', '', '', 'Q', 'q'],
        ]);

        /* white */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 0, 'y' => 0]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 1, 'y' => 7]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 7]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 1]);
        $this->assertTrue($moveInfo->getStatus());

        /* black */

        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);

        $moveInfo = $chessman->canMove(['x' => 1, 'y' => 0]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 0, 'y' => 7]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 6]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 0]);
        $this->assertTrue($moveInfo->getStatus());
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_diagonal_capture_false(): void
    {
        $board = GameBoard::createByBoard([
            ['Q', '', '', '', '', '', '', 'Q'],
            ['q', '', '', '', '', '', '', 'q'],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', 'q', 'Q', '', ''],
            ['', '', '', 'b', 'B', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'q', 'q', ''],
            ['q', 'Q', '', '', '', '', 'Q', 'q'],
        ]);

        /* white */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 0, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 1, 'y' => 7]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 7]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());

        /* black */

        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);

        $moveInfo = $chessman->canMove(['x' => 1, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 0, 'y' => 7]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());
    }

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
