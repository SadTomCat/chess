<?php

namespace Tests\Unit\Chessmen;

use App\Game\GameBoard;
use PHPUnit\Framework\TestCase;

class PawnMoveTest extends TestCase
{
    /* White */
    /**
     * @return void
     */
    public function test_white_one_forward_peace_move(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', 'q', 'Q', '', '', '', '', ''],
            ['P', 'P', 'P', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $chessman = $board->getChessman(['x' => 6, 'y' => 0]);
        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 0]);
        $this->assertTrue($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 6, 'y' => 1]);
        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 6, 'y' => 2]);
        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());
    }

    /**
     * @return void
     */
    public function test_white_one_forward_capture_move(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', 'q', '', 'Q', '', '', 'q', ''],
            ['P', '', 'P', '', 'P', '', '', 'P'],
            ['', '', '', '', '', '', '', ''],
        ]);

        $chessman = $board->getChessman(['x' => 6, 'y' => 0]);
        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 1]);
        $this->assertTrue($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 6, 'y' => 2]);
        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());


        $chessman = $board->getChessman(['x' => 6, 'y' => 4]);
        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 5]);
        $this->assertFalse($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 6, 'y' => 7]);
        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 6]);
        $this->assertTrue($moveInfo->getStatus());

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['Q', '', '', '', '', '', '', ''],
            ['', 'P', '', 'P', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $chessman = $board->getChessman(['x' => 6, 'y' => 1]);
        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 6, 'y' => 3]);
        $moveInfo = $chessman->canMove(['x' => 5, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());
    }

    /**
     * @return void
     */
    public function test_white_two_forward_peace_move(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', 'q', 'Q', '', 'Q', 'q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['P', 'P', 'P', 'P', 'P', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $chessman = $board->getChessman(['x' => 6, 'y' => 0]);
        $moveInfo = $chessman->canMove(['x' => 4, 'y' => 0]);
        $this->assertTrue($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 6, 'y' => 1]);
        $moveInfo = $chessman->canMove(['x' => 4, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 6, 'y' => 2]);
        $moveInfo = $chessman->canMove(['x' => 4, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 6, 'y' => 3]);
        $moveInfo = $chessman->canMove(['x' => 4, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 6, 'y' => 4]);
        $moveInfo = $chessman->canMove(['x' => 4, 'y' => 5]);
        $this->assertFalse($moveInfo->getStatus());
    }

    /**
     * @return void
     */
    public function test_white_more_then_one_forward(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', 'Q', 'q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['P', 'P', 'P', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $chessman = $board->getChessman(['x' => 6, 'y' => 0]);
        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 6, 'y' => 1]);
        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 6, 'y' => 2]);
        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['P', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $chessman = $board->getChessman(['x' => 6, 'y' => 0]);
        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());
    }

    public function test_white_horizontal_or_back(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'P', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);
        $chessman = $board->getChessman(['x' => 6, 'y' => 3]);

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', 'Q', '', 'P', '', 'Q', '', ''],
            ['', '', 'Q', '', 'q', '', '', ''],
        ]);
        $chessman = $board->getChessman(['x' => 6, 'y' => 3]);

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 5]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());
    }

    /* Black */
    /**
     * @return void
     */
    public function test_black_one_forward_peace_move(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['p', 'p', 'p', '', '', '', '', ''],
            ['', 'Q', 'q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $chessman = $board->getChessman(['x' => 1, 'y' => 0]);
        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 0]);
        $this->assertTrue($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 1, 'y' => 1]);
        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 1, 'y' => 2]);
        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());
    }

    /**
     * @return void
     */
    public function test_black_one_forward_capture_move(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['p', '', 'p', '', 'p', '', '', 'p'],
            ['', 'Q', '', 'q', '', '', 'Q', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', 'q', '', 'Q', '', '', 'q', ''],
            ['P', '', 'P', '', 'P', '', '', 'P'],
            ['', '', '', '', '', '', '', ''],
        ]);

        $chessman = $board->getChessman(['x' => 1, 'y' => 0]);
        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 1]);
        $this->assertTrue($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 1, 'y' => 2]);
        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());


        $chessman = $board->getChessman(['x' => 1, 'y' => 4]);
        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 5]);
        $this->assertFalse($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 1, 'y' => 7]);
        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 6]);
        $this->assertTrue($moveInfo->getStatus());

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', 'p', '', 'p', '', '', '', ''],
            ['q', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['Q', '', '', '', '', '', '', ''],
            ['', 'P', '', 'P', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $chessman = $board->getChessman(['x' => 1, 'y' => 1]);
        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 1, 'y' => 3]);
        $moveInfo = $chessman->canMove(['x' => 2, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());
    }

    /**
     * @return void
     */
    public function test_black_two_forward_peace_move(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['p', 'p', 'p', 'p', 'p', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', 'Q', 'q', '', 'q', 'Q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '','', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $chessman = $board->getChessman(['x' => 1, 'y' => 0]);
        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 0]);
        $this->assertTrue($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 1, 'y' => 1]);
        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 1, 'y' => 2]);
        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 1, 'y' => 3]);
        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 1, 'y' => 4]);
        $moveInfo = $chessman->canMove(['x' => 3, 'y' => 5]);
        $this->assertFalse($moveInfo->getStatus());
    }

    /**
     * @return void
     */
    public function test_black_more_then_one_forward(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['p', 'p', 'p', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', 'q', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $chessman = $board->getChessman(['x' => 1, 'y' => 0]);
        $moveInfo = $chessman->canMove(['x' => 4, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 1, 'y' => 1]);
        $moveInfo = $chessman->canMove(['x' => 4, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());

        $chessman = $board->getChessman(['x' => 1, 'y' => 2]);
        $moveInfo = $chessman->canMove(['x' => 4, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['p', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $chessman = $board->getChessman(['x' => 1, 'y' => 0]);
        $moveInfo = $chessman->canMove(['x' => 4, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());
    }

    public function test_black_horizontal_or_back(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'P', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);
        $chessman = $board->getChessman(['x' => 6, 'y' => 3]);

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 6, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 7, 'y' => 3]);
        $this->assertFalse($moveInfo->getStatus());

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', 'Q', '', 'p', '', 'q', '', ''],
            ['', '', 'q', '', 'Q', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);
        $chessman = $board->getChessman(['x' => 1, 'y' => 3]);

        $moveInfo = $chessman->canMove(['x' => 1, 'y' => 1]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 1, 'y' => 5]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 1, 'y' => 0]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 1, 'y' => 6]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 0, 'y' => 2]);
        $this->assertFalse($moveInfo->getStatus());

        $moveInfo = $chessman->canMove(['x' => 0, 'y' => 4]);
        $this->assertFalse($moveInfo->getStatus());
    }
}
