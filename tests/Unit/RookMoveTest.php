<?php

namespace Tests\Unit;

use App\Game\GameBoard;
use PHPUnit\Framework\TestCase;

class RookMoveTest extends TestCase
{
    /**
     * @return void
     */
    public function test_vertical_peace_true(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'r', 'R', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 0, 'y' => 4]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 4]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 4]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 4]);
        $this->assertTrue($moveInfo->getStatus());

        /* black */

        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);

        $moveInfo = $chessman->canMove(['x' => 0, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());
    }

    /**
     * @return void
     */
    public function test_vertical_peace_false(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', 'Q', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'r', 'R', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', 'q', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white  */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 0, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        /* black  */

        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);

        $moveInfo = $chessman->canMove(['x' => 0, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());
    }

    /**
     * @return void
     */
    public function test_vertical_capture(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', 'q', '', '', ''],
            ['', '', '', 'q', 'Q', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'r', 'R', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', 'q', '', '', ''],
            ['', '', '', 'Q', 'q', '', '', ''],
        ]);

        /* white  */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 1, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 4]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        /* black  */

        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);

        $moveInfo = $chessman->canMove(['x' => 1, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());
    }

    /**
     * @return void
     */
    public function test_horizontal_peace_true(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', 'R', '', '', ''],
            ['', '', '', '', 'r', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white */

        $chessman = $board->getChessman(['x' => 3, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 0]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 5]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 7]);
        $this->assertTrue($moveInfo->getStatus());


        /* black */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 4, 'y' => 0]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 4, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 4, 'y' => 5]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 4, 'y' => 7]);
        $this->assertTrue($moveInfo->getStatus());
    }

    /**
     * @return void
     */
    public function test_horizontal_peace_false(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', 'q', '', 'R', '', 'Q', ''],
            ['', '', 'Q', '', 'r', '', 'q', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white  */

        $chessman = $board->getChessman(['x' => 3, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 7]);
        $this->assertFalse($moveInfo->getStatus());

        /* black  */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 4, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 4, 'y' => 7]);
        $this->assertFalse($moveInfo->getStatus());
    }

    /**
     * @return void
     */
    public function test_horizontal_capture(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', 'q', 'Q', '', 'R', '', 'q', 'q'],
            ['', 'Q', 'q', '', 'r', '', 'Q', 'Q'],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white  */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 4, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 4, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 4, 'y' => 6]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 4, 'y' => 7]);
        $this->assertFalse($moveInfo->getStatus());

        /* black  */

        $chessman = $board->getChessman(['x' => 5, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 6]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 7]);
        $this->assertFalse($moveInfo->getStatus());
    }

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
