<?php

namespace Tests\Unit\Chessmen;

use App\Game\GameBoard;

trait DiagonalMoveTest
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_diagonal_peace_true(): void
    {
        $w = ucfirst($this->chessman);
        $b = lcfirst($this->chessman);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', "$b", "$w", '', '', ''],
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
        $w = ucfirst($this->chessman);
        $b = lcfirst($this->chessman);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', 'N', '', '', '', '', '', ''],
            ['', '', '', '', '', 'N', '', ''],
            ['', '', 'n', '', '', 'n', '', ''],
            ['', '', '', "$b", "$w", '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', 'N', '', '', '', 'n', 'N', ''],
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
        $w = ucfirst($this->chessman);
        $b = lcfirst($this->chessman);

        $board = GameBoard::createByBoard([
            ['n', '', '', '', '', '', '', 'N'],
            ['N', '', '', '', '', '', '', 'n'],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', "$b", "$w", '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['N', 'n', '', '', '', '', 'N', 'n'],
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
        $w = ucfirst($this->chessman);
        $b = lcfirst($this->chessman);

        $board = GameBoard::createByBoard([
            ['N', '', '', '', '', '', '', 'N'],
            ['n', '', '', '', '', '', '', 'n'],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', 'n', 'N', '', ''],
            ['', '', '', "$b", "$w", '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'n', 'n', ''],
            ['n', 'N', '', '', '', '', 'N', 'n'],
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
}
