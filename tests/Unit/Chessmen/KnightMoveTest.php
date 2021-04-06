<?php

namespace Tests\Unit\Chessmen;

use App\Game\GameBoard;
use PHPUnit\Framework\TestCase;

class KnightMoveTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function test_peace_true(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', 'N', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 5]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 6]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 6]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 5]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 2]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 2]);
        $this->assertTrue($moveInfo->getStatus());

        /* black */
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', 'n', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 5]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 6]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 6]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 5]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 2]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 2]);
        $this->assertTrue($moveInfo->getStatus());
    }

    /**
     * @return void
     */
    public function test_peace_false(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'n', 'N', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 5]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 0, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 4, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 1, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        /* black */
        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);

        $moveInfo = $chessman->canMove(['x' => 0, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());
    }

    /**
     * @return void
     */
    public function test_diagonal_capture_true(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', 'q', '', ''],
            ['', '', 'q', '', '', '', 'q', ''],
            ['', '', '', '', 'N', '', '', ''],
            ['', '', 'q', '', '', '', 'q', ''],
            ['', '', '', 'q', '', 'q', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 5]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 6]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 6]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 5]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 2]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 2]);
        $this->assertTrue($moveInfo->getStatus());


        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', 'Q', '', ''],
            ['', '', 'Q', '', '', '', 'Q', ''],
            ['', '', '', '', 'n', '', '', ''],
            ['', '', 'Q', '', '', '', 'Q', ''],
            ['', '', '', 'Q', '', 'Q', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* black */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 5]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 6]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 6]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 5]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 2]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 2]);
        $this->assertTrue($moveInfo->getStatus());
    }
    /**
     * @return void
     */
    public function test_capture_false(): void
    {

        $board = GameBoard::createByBoard([
            ['q', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', 'Q', '', ''],
            ['', '', 'Q', 'q', '', '', 'Q', ''],
            ['', '', '', '', 'N', '', '', ''],
            ['', '', 'Q', '', '', '', 'Q', ''],
            ['', '', 'q', 'Q', '', 'Q', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 5]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 5]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 0, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $board = GameBoard::createByBoard([
            ['Q', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', 'q', '', ''],
            ['', '', 'q', 'Q', '', '', 'q', ''],
            ['', '', '', '', 'n', '', '', ''],
            ['', '', 'q', '', '', '', 'q', ''],
            ['', '', 'Q', 'q', '', 'q', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* black */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 5]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 5]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 0, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());
    }
}
