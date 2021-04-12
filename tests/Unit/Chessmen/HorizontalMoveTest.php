<?php

namespace Tests\Unit\Chessmen;

use App\Game\GameBoard;

trait HorizontalMoveTest
{
    /**
     * @return void
     */
    public function test_horizontal_peace_true(): void
    {
        $w = ucfirst($this->chessman);
        $b = lcfirst($this->chessman);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', "$w", '', '', ''],
            ['', '', '', '', "$b", '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white */

        $chessman = $board->getChessman(['x' => 3, 'y' => 4]);

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 0]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 5]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 7]);
        $this->assertTrue($moveInfo->getStatus());


        /* black */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 0]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 3]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 5]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 7]);
        $this->assertTrue($moveInfo->getStatus());
    }

    /**
     * @return void
     */
    public function test_horizontal_peace_false(): void
    {
        $w = ucfirst($this->chessman);
        $b = lcfirst($this->chessman);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', 'n', '', "$w", '', 'N', ''],
            ['', '', 'N', '', "$b", '', 'n', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white  */

        $chessman = $board->getChessman(['x' => 3, 'y' => 4]);

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 3, 'y' => 7]);
        $this->assertFalse($moveInfo->getStatus());

        /* black  */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 7]);
        $this->assertFalse($moveInfo->getStatus());
    }

    /**
     * @return void
     */
    public function test_horizontal_capture(): void
    {
        $w = ucfirst($this->chessman);
        $b = lcfirst($this->chessman);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', 'n', 'N', '', "$w", '', 'n', 'n'],
            ['', 'N', 'n', '', "$b", '', 'N', 'N'],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        /* white  */

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 6]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 4, 'y' => 7]);
        $this->assertFalse($moveInfo->getStatus());

        /* black  */

        $chessman = $board->getChessman(['x' => 5, 'y' => 4]);

        $moveInfo = $chessman->validMoveByRule(['x' => 5, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 5, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 5, 'y' => 6]);
        $this->assertTrue($moveInfo->getStatus());

        $moveInfo = $chessman->validMoveByRule(['x' => 5, 'y' => 7]);
        $this->assertFalse($moveInfo->getStatus());
    }
}
