<?php

namespace Tests\Unit\Chessmen;

use App\Game\Chessmen\King;
use App\Game\Chessmen\NullChessman;
use App\Game\Chessmen\Queen;
use App\Game\GameBoard;
use PHPUnit\Framework\TestCase;

/*
            0 [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '],
            1 [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '],
            2 [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '],
            3 [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '],
            4 [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '],
            5 [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '],
            6 [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '],
            7 [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '],
                0    1    2    3    4    5    6    7
*/
class WillKingSafeAfterMoveTest extends TestCase
{
    /*  Horizontal */

    public function test_will_safe_on_horizontal_left(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', 'Q', 'K', 'q', '', ''],
            ['', 'Q', '', 'Q', 'K', 'q', '', ''],
            ['q', 'Q', '', 'Q', 'K', 'q', '', ''],
            ['', 'q', '', 'Q', 'K', 'q', '', ''],
            ['', 'r', '', 'Q', 'K', '', '', ''],
            ['', 'p', '', 'Q', 'K', '', '', ''],
            ['', 'b', '', 'Q', 'K', '', '', ''],
            ['', 'n', '', 'Q', 'K', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 0, 'y' => 4]);
        $chessman = $board->getChessman(['x' => 0, 'y' => 3]);
        $res = $chessman->safetyMoveOnHorizontal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 1, 'y' => 4]);
        $chessman = $board->getChessman(['x' => 1, 'y' => 3]);
        $res = $chessman->safetyMoveOnHorizontal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 2, 'y' => 4]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 3]);
        $res = $chessman->safetyMoveOnHorizontal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 2, 'y' => 4]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 1]);
        $res = $chessman->safetyMoveOnHorizontal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 3, 'y' => 4]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 3]);
        $res = $chessman->safetyMoveOnHorizontal($king->getPosition());
        $this->assertFalse($res);

        $king = $board->getChessman(['x' => 4, 'y' => 4]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);
        $res = $chessman->safetyMoveOnHorizontal($king->getPosition());
        $this->assertFalse($res);

        $king = $board->getChessman(['x' => 5, 'y' => 4]);
        $chessman = $board->getChessman(['x' => 5, 'y' => 3]);
        $res = $chessman->safetyMoveOnHorizontal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 6, 'y' => 4]);
        $chessman = $board->getChessman(['x' => 6, 'y' => 3]);
        $res = $chessman->safetyMoveOnHorizontal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 7, 'y' => 4]);
        $chessman = $board->getChessman(['x' => 7, 'y' => 3]);
        $res = $chessman->safetyMoveOnHorizontal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', 'q', 'p', 'Q', 'K', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 6, 'y' => 4]);
        $chessman = $board->getChessman(['x' => 6, 'y' => 3]);
        $res = $chessman->safetyMoveOnHorizontal($king->getPosition());
        $this->assertTrue($res);
    }

    public function test_will_safe_on_horizontal_right(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', 'Q', 'k', 'q', '', '', ''],
            ['', '', 'Q', 'k', 'q', '', 'q', ''],
            ['', '', 'Q', 'k', 'q', 'q', 'Q', ''],
            ['', '', 'Q', 'k', 'q', '', 'Q', ''],
            ['', '', '', 'k', 'q', '', 'R', ''],
            ['', '', '', 'k', 'q', '', 'P', ''],
            ['', '', '', 'k', 'q', '', 'B', ''],
            ['', '', '', 'k', 'q', '', 'K', ''],
        ]);

        $king = $board->getChessman(['x' => 0, 'y' => 3]);
        $chessman = $board->getChessman(['x' => 0, 'y' => 4]);
        $res = $chessman->safetyMoveOnHorizontal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 1, 'y' => 3]);
        $chessman = $board->getChessman(['x' => 1, 'y' => 4]);
        $res = $chessman->safetyMoveOnHorizontal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 2, 'y' => 3]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 4]);
        $res = $chessman->safetyMoveOnHorizontal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 2, 'y' => 3]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 5]);
        $res = $chessman->safetyMoveOnHorizontal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 3, 'y' => 3]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 4]);
        $res = $chessman->safetyMoveOnHorizontal($king->getPosition());
        $this->assertFalse($res);

        $king = $board->getChessman(['x' => 4, 'y' => 3]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);
        $res = $chessman->safetyMoveOnHorizontal($king->getPosition());
        $this->assertFalse($res);

        $king = $board->getChessman(['x' => 5, 'y' => 3]);
        $chessman = $board->getChessman(['x' => 5, 'y' => 4]);
        $res = $chessman->safetyMoveOnHorizontal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 6, 'y' => 3]);
        $chessman = $board->getChessman(['x' => 6, 'y' => 4]);
        $res = $chessman->safetyMoveOnHorizontal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 7, 'y' => 3]);
        $chessman = $board->getChessman(['x' => 7, 'y' => 4]);
        $res = $chessman->safetyMoveOnHorizontal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'k', 'q', 'B', 'Q', ''],
        ]);

        $king = $board->getChessman(['x' => 7, 'y' => 3]);
        $chessman = $board->getChessman(['x' => 7, 'y' => 4]);
        $res = $chessman->safetyMoveOnHorizontal($king->getPosition());
        $this->assertTrue($res);
    }

    /*  Vertical */

    public function test_will_safe_on_vertical_top(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', 'Q', 'q', 'q', 'r', 'p', 'k', 'n'],
            ['', '', 'Q', '', '', '', '', ''],
            ['Q', 'Q', 'Q', 'Q', 'Q', 'P', 'P', 'P'],
            ['K', 'K', 'K', 'K', 'K', 'K', 'K', 'K'],
            ['q', 'q', 'q', 'q', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 0]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 0]);
        $res = $chessman->safetyMoveOnVertical($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 5, 'y' => 1]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 1]);
        $res = $chessman->safetyMoveOnVertical($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 5, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 2]);
        $res = $chessman->safetyMoveOnVertical($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 5, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 2]);
        $res = $chessman->safetyMoveOnVertical($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 5, 'y' => 3]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);
        $res = $chessman->safetyMoveOnVertical($king->getPosition());
        $this->assertFalse($res);

        $king = $board->getChessman(['x' => 5, 'y' => 4]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);
        $res = $chessman->safetyMoveOnVertical($king->getPosition());
        $this->assertFalse($res);

        $king = $board->getChessman(['x' => 5, 'y' => 5]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 5]);
        $res = $chessman->safetyMoveOnVertical($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 5, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 6]);
        $res = $chessman->safetyMoveOnVertical($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 5, 'y' => 7]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 7]);
        $res = $chessman->safetyMoveOnVertical($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', 'q'],
            ['', '', '', '', '', '', '', 'k'],
            ['', '', '', '', '', '', '', 'P'],
            ['', '', '', '', '', '', '', 'K'],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 7]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 7]);
        $res = $chessman->safetyMoveOnVertical($king->getPosition());
        $this->assertTrue($res);
    }

    public function test_will_safe_on_vertical_bottom(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['Q', 'Q', 'Q', 'Q', '', '', '', ''],
            ['k', 'k', 'k', 'k', 'k', 'k', 'k', 'k'],
            ['q', 'q', 'q', 'q', 'q', 'q', 'q', 'q'],
            ['', '', '', '', '', '', '', ''],
            ['', 'q', 'q', '', '', '', '', ''],
            ['', '', 'Q', 'Q', 'R', 'N', 'B', 'P'],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 2, 'y' => 0]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 0]);
        $res = $chessman->safetyMoveOnVertical($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 2, 'y' => 1]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 1]);
        $res = $chessman->safetyMoveOnVertical($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 2, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 2]);
        $res = $chessman->safetyMoveOnVertical($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 2, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 5, 'y' => 2]);
        $res = $chessman->safetyMoveOnVertical($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 2, 'y' => 3]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 3]);
        $res = $chessman->safetyMoveOnVertical($king->getPosition());
        $this->assertFalse($res);

        $king = $board->getChessman(['x' => 2, 'y' => 4]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 4]);
        $res = $chessman->safetyMoveOnVertical($king->getPosition());
        $this->assertFalse($res);

        $king = $board->getChessman(['x' => 2, 'y' => 5]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 5]);
        $res = $chessman->safetyMoveOnVertical($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 2, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 6]);
        $res = $chessman->safetyMoveOnVertical($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 2, 'y' => 7]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 7]);
        $res = $chessman->safetyMoveOnVertical($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', 'k'],
            ['', '', '', '', '', '', '', 'q'],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', 'N'],
            ['', '', '', '', '', '', '', 'Q'],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 2, 'y' => 7]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 7]);
        $res = $chessman->safetyMoveOnVertical($king->getPosition());
        $this->assertTrue($res);
    }

    /* Main diagonal */

    /* Main diagonal top */

    /**
     * @throws \Exception
     */
    public function test_will_safe_on_middle_diagonal_top(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', 'K', '', '', '', '', '', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 1]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 2]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertFalse($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', 'K', '', '', '', '', '', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', 'b', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 1]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 2]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertFalse($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', 'K', '', '', '', '', '', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', 'Q', '', '', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 1]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 2]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 1, 'y' => 1]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', 'K', '', '', '', '', '', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', 'Q', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 1]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 2]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', 'K', '', '', '', '', '', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 1]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 2]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', 'K', '', '', '', '', '', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', 'p', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 1]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 2]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', 'K', '', '', '', '', '', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', 'n', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 1]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 2]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', 'K', '', '', '', '', '', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', 'r', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 1]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 2]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', 'K', '', '', '', '', '', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', 'k', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 1]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 2]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', 'K', '', '', '', '', '', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', 'k', '', '', '', ''],
            ['', '', '', '', 'b', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 1]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 2]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);
    }

    /**
     * @throws \Exception
     */
    public function test_will_safe_on_top_diagonal_top(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', 'k', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', 'Q', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 3]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 1, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 5]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', 'k', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 3]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', 'k', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 3]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', 'k', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 3]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertFalse($res);
    }

    /**
     * @throws \Exception
     */
    public function test_will_safe_on_bottom_diagonal_top(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', 'K', '', '', '', '', '', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', '', '', 'q', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 2, 'y' => 1]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 2]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', 'K', '', '', '', '', '', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 2, 'y' => 1]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 2]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', 'K', '', '', '', '', '', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', 'Q', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 2, 'y' => 1]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 2]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', 'K', '', '', '', '', '', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', 'q', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 2, 'y' => 1]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 2]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertFalse($res);
    }

    /* Main diagonal bottom */

    /**
     * @throws \Exception
     */
    public function test_will_safe_on_middle_diagonal_bottom(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', 'Q', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 6, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 5, 'y' => 5]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 6, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 3]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 6, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 5, 'y' => 5]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 6, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 5, 'y' => 5]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', 'P', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 6, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 5, 'y' => 5]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', 'K', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 6, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 5, 'y' => 5]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', 'R', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 6, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 5, 'y' => 5]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', 'N', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 6, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 5, 'y' => 5]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 6, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 5, 'y' => 5]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertFalse($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', 'B', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 6, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 5, 'y' => 5]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertFalse($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', 'R', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 6, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 5, 'y' => 5]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);
    }

    /**
     * @throws \Exception
     */
    public function test_will_safe_on_top_diagonal_bottom(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', 'q', '', '', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', '', '', 'K', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 5]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 5, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 3]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', '', '', 'K', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 5]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', '', '', 'K', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 5]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', 'q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', '', '', 'K', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 5]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertFalse($res);
    }

    /**
     * @throws \Exception
     */
    public function test_will_safe_on_bottom_diagonal_bottom(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', 'Q', '', '', '', '', '', ''],
            ['', '', 'q', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', '', '', '', 'k', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 4]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $chessman = $board->getChessman(['x' => 3, 'y' => 2]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', '', '', '', 'k', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 4]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', 'q', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', '', '', '', 'k', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 4]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', 'Q', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', '', '', '', 'k', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 4]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);
        $res = $chessman->safetyMoveOnMainDiagonal($king->getPosition());
        $this->assertFalse($res);
    }

    /* Anti diagonal */

    /* Anti diagonal right */

    /**
     * @throws \Exception
     */
    public function test_will_safe_on_middle_anti_diagonal_right(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertFalse($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'B', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertFalse($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['Q', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertFalse($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', 'q', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 1, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 4]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'N', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'P', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'K', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'R', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', 'k', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', 'P', '', '', ''],
            ['', '', '', 'B', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 1, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);
    }

    /**
     * @throws \Exception
     */
    public function test_will_safe_on_top_anti_diagonal_right(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', 'K', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 2, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertFalse($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', 'K', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', 'q', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 2, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertFalse($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', 'K', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', 'Q', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 2, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 2, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', 'K', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 2, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', 'K', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 2, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);
    }

    /**
     * @throws \Exception
     */
    public function test_will_safe_on_bottom_anti_diagonal_right(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', 'K', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 0, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 1, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertFalse($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', 'K', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['q', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 0, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 1, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertFalse($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', 'K', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', 'Q', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 0, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 1, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 0, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 4]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', 'K', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 0, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 1, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', 'K', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 0, 'y' => 6]);
        $chessman = $board->getChessman(['x' => 1, 'y' => 5]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);
    }

    /* Anti diagonal left */

    /**
     * @throws \Exception
     */
    public function test_will_safe_on_middle_anti_diagonal_left(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', 'K', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertFalse($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'b', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', 'K', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertFalse($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', 'q'],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', 'K', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertFalse($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', 'Q', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', 'K', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 5, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 4]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', 'K', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);


        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', 'K', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'p', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', 'K', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'P', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', 'K', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'k', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', 'K', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'r', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', 'K', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', 'n', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', 'K', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 5, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);
    }

    /**
     * @throws \Exception
     */
    public function test_will_safe_on_top_anti_diagonal_left(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', 'K', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 4, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertFalse($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', 'q', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', 'K', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 4, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertFalse($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', 'Q', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', 'K', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 4, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 4, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 2, 'y' => 4]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', 'K', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 4, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);


        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', 'K', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 4, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 3, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);
    }

    /**
     * @throws \Exception
     */
    public function test_will_safe_on_right_anti_diagonal_bottom(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', '', 'k', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 6, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 5, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertFalse($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', '', 'k', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 6, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 5, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertFalse($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', '', 'q', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', '', 'k', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 6, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 5, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $king = $board->getChessman(['x' => 6, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);

        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', 'q', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', '', 'k', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 6, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 5, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);


        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
            ['', '', 'k', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $king = $board->getChessman(['x' => 6, 'y' => 2]);
        $chessman = $board->getChessman(['x' => 5, 'y' => 3]);
        $res = $chessman->safetyMoveOnAntiDiagonal($king->getPosition());
        $this->assertTrue($res);
    }

    /* Will safe */
    public function test_will_safe_after_move(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['Q', '', 'Q', 'Q', 'Q', '', 'Q', ''],
            ['', '', '', '', '', '', '', ''],
            ['Q', '', 'q', 'q', 'q', '', 'Q', ''],
            ['Q', '', 'q', 'k', 'q', '', 'Q', ''],
            ['', '', 'q', 'q', 'q', '', 'Q', ''],
            ['', '', '', '', '', '', '', ''],
            ['Q', '', 'Q', 'Q', 'Q', '', 'Q', ''],
        ]);

        $chessman = $board->getChessman(['x' => 3, 'y' => 2]);
        $res = $chessman->willKingSafeAfterMove(['x' => 2, 'y' => 1]);
        $this->assertTrue($res);

        $chessman = $board->getChessman(['x' => 3, 'y' => 2]);
        $res = $chessman->willKingSafeAfterMove(['x' => 0, 'y' => 0]);
        $this->assertFalse($res);

        $chessman = $board->getChessman(['x' => 3, 'y' => 3]);
        $res = $chessman->willKingSafeAfterMove(['x' => 2, 'y' => 3]);
        $this->assertTrue($res);

        $chessman = $board->getChessman(['x' => 3, 'y' => 3]);
        $res = $chessman->willKingSafeAfterMove(['x' => 0, 'y' => 0]);
        $this->assertFalse($res);

        $chessman = $board->getChessman(['x' => 3, 'y' => 4]);
        $res = $chessman->willKingSafeAfterMove(['x' => 2, 'y' => 5]);
        $this->assertTrue($res);

        $chessman = $board->getChessman(['x' => 3, 'y' => 4]);
        $res = $chessman->willKingSafeAfterMove(['x' => 0, 'y' => 0]);
        $this->assertFalse($res);

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);
        $res = $chessman->willKingSafeAfterMove(['x' => 4, 'y' => 5]);
        $this->assertTrue($res);

        $chessman = $board->getChessman(['x' => 4, 'y' => 4]);
        $res = $chessman->willKingSafeAfterMove(['x' => 0, 'y' => 0]);
        $this->assertFalse($res);

        $chessman = $board->getChessman(['x' => 5, 'y' => 4]);
        $res = $chessman->willKingSafeAfterMove(['x' => 6, 'y' => 5]);
        $this->assertTrue($res);

        $chessman = $board->getChessman(['x' => 5, 'y' => 4]);
        $res = $chessman->willKingSafeAfterMove(['x' => 0, 'y' => 0]);
        $this->assertFalse($res);

        $chessman = $board->getChessman(['x' => 5, 'y' => 3]);
        $res = $chessman->willKingSafeAfterMove(['x' => 6, 'y' => 3]);
        $this->assertTrue($res);

        $chessman = $board->getChessman(['x' => 5, 'y' => 3]);
        $res = $chessman->willKingSafeAfterMove(['x' => 0, 'y' => 0]);
        $this->assertFalse($res);

        $chessman = $board->getChessman(['x' => 5, 'y' => 2]);
        $res = $chessman->willKingSafeAfterMove(['x' => 6, 'y' => 1]);
        $this->assertTrue($res);

        $chessman = $board->getChessman(['x' => 5, 'y' => 2]);
        $res = $chessman->willKingSafeAfterMove(['x' => 0, 'y' => 0]);
        $this->assertFalse($res);

        $chessman = $board->getChessman(['x' => 4, 'y' => 2]);
        $res = $chessman->willKingSafeAfterMove(['x' => 4, 'y' => 1]);
        $this->assertTrue($res);

        $chessman = $board->getChessman(['x' => 4, 'y' => 2]);
        $res = $chessman->willKingSafeAfterMove(['x' => 0, 'y' => 0]);
        $this->assertFalse($res);
    }

}
