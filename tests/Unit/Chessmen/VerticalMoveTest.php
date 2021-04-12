<?php

namespace Tests\Unit\Chessmen;

use App\Game\GameBoard;

trait VerticalMoveTest
{
    /**
     * @return void
     */
    public function test_vertical_peace_true(): void
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

        $moveInfo = $chessman->validMoveByRule(['x' => 0, 'y' => 4]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 4]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 5, 'y' => 4]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 7, 'y' => 4]);
        $this->assertTrue($moveInfo->getStatus());

        /* black */

        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);

        $moveInfo = $chessman->validMoveByRule(['x' => 0, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 5, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 7, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());
    }

    /**
     * @return void
     */
    public function test_vertical_peace_false(): void
    {
        $w = ucfirst($this->chessman);
        $b = lcfirst($this->chessman);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'n', 'N', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', "$b", "$w", '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'N', 'n', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white  */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->validMoveByRule(['x' => 0, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 7, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        /* black  */

        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);

        $moveInfo = $chessman->validMoveByRule(['x' => 0, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 7, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());
    }

    /**
     * @return void
     */
    public function test_vertical_capture(): void
    {
        $w = ucfirst($this->chessman);
        $b = lcfirst($this->chessman);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'N', 'n', '', '', ''],
            ['', '', '', 'n', 'N', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', "$b", "$w", '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'N', 'n', '', '', ''],
            ['', '', '', 'N', 'n', '', '', ''],
        ]);

        /* white  */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->validMoveByRule(['x' => 1, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 2, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 6, 'y' => 4]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 7, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        /* black  */

        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);

        $moveInfo = $chessman->validMoveByRule(['x' => 1, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 2, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 6, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 7, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());
    }
}
