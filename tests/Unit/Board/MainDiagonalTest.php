<?php

namespace Tests\Unit\Board;

use App\Game\GameBoard;
use PHPUnit\Framework\TestCase;

/*
            0 [' ', ' ', ' ', 'q', ' ', ' ', ' ', ' '],
            1 [' ', 'q', ' ', ' ', 'q', ' ', ' ', ' '],
            2 [' ', ' ', ' ', ' ', ' ', 'Q', ' ', ' '],
            3 [' ', ' ', ' ', 'Q', ' ', ' ', ' ', ' '],
            4 [' ', ' ', ' ', ' ', 'q', ' ', ' ', ' '],
            5 [' ', 'Q', ' ', ' ', ' ', ' ', ' ', ' '],
            6 [' ', ' ', 'q', ' ', ' ', ' ', ' ', ' '],
            7 [' ', ' ', ' ', 'q', ' ', ' ', ' ', ' '],
                0    1    2    3    4    5    6    7
*/
class MainDiagonalTest extends TestCase
{
    /* Get all */

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_all_on_diagonal_by_number(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', 'q', '', '', '', ''],
            ['', 'q', '', '', 'q', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', '', '', 'q', '', '', ''],
            ['', 'Q', '', '', '', '', '', ''],
            ['', '', 'q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $res = json_encode($board->getAllOnDiagonal());
        $this->assertJsonStringEqualsJsonString($res, '[{"x":1,"y":1},{"x":3,"y":3},{"x":4,"y":4}]');

        $res = json_encode($board->getAllOnDiagonal(3));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":0,"y":3},{"x":1,"y":4},{"x":2,"y":5}]');

        $res = json_encode($board->getAllOnDiagonal(-4));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":5,"y":1},{"x":6,"y":2}]');

        $res = json_encode($board->getAllOnDiagonal(5));
        $this->assertJsonStringEqualsJsonString($res, '[]');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_all_on_diagonal_between(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', 'q', '', '', '', ''],
            ['', 'q', '', '', 'q', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', '', '', 'q', '', '', ''],
            ['', 'Q', '', '', '', '', '', ''],
            ['', '', 'q', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
        ]);

        $res = json_encode($board->getAllOnDiagonal(startPos: ['x' => 3, 'y' => 3]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":3,"y":3},{"x":4,"y":4}]');

        $res = json_encode($board->getAllOnDiagonal(startPos: ['x' => 1, 'y' => 4]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":1,"y":4},{"x":2,"y":5}]');

        $res = json_encode($board->getAllOnDiagonal(startPos: ['x' => 5, 'y' => 1], endPos: ['x' => 6, 'y' => 2]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":5,"y":1},{"x":6,"y":2}]');

        $res = json_encode($board->getAllOnDiagonal(startPos: ['x' => 6, 'y' => 2], endPos: ['x' => 7, 'y' => 3]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":6,"y":2},{"x":7,"y":3}]');

        $res = json_encode($board->getAllOnDiagonal(endPos: ['x' => 6, 'y' => 2]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":5,"y":1},{"x":6,"y":2}]');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_all_on_diagonal_with_color(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', 'q', '', '', '', ''],
            ['', 'q', '', '', 'q', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', '', '', 'q', '', '', ''],
            ['', 'Q', '', '', '', '', '', ''],
            ['', '', 'q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $res = json_encode($board->getAllOnDiagonal(color: 'white'));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":3,"y":3}]');

        $res = json_encode($board->getAllOnDiagonal(color: 'black'));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":1,"y":1},{"x":4,"y":4}]');

        $res = json_encode($board->getAllOnDiagonal(3, color: 'white'));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":2,"y":5}]');

        $res = json_encode($board->getAllOnDiagonal(3, color: 'black'));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":0,"y":3},{"x":1,"y":4}]');

        $res = json_encode($board->getAllOnDiagonal(-4, color: 'white'));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":5,"y":1}]');

        $res = json_encode($board->getAllOnDiagonal(-4, color: 'black'));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":6,"y":2}]');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_all_on_diagonal_by_number_reverse(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', 'q', '', '', '', ''],
            ['', 'q', '', '', 'q', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', '', '', 'q', '', '', ''],
            ['', 'Q', '', '', '', '', '', ''],
            ['', '', 'q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $res = json_encode($board->getAllOnDiagonal(startPos: ['x' => 7,'y' => 7], endPos: ['x' => 0, 'y' => 0]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":4,"y":4},{"x":3,"y":3},{"x":1,"y":1}]');

        $res = json_encode($board->getAllOnDiagonal(startPos: ['x' => 4,'y' => 7], endPos: ['x' => 0, 'y' => 3]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":2,"y":5},{"x":1,"y":4},{"x":0,"y":3}]');

        $res = json_encode($board->getAllOnDiagonal(startPos: ['x' => 7,'y' => 3], endPos: ['x' => 4, 'y' => 0]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":6,"y":2},{"x":5,"y":1}]');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_all_on_diagonal_between_reverse(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', 'q', '', '', '', ''],
            ['', 'q', '', '', 'q', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', '', '', 'q', '', '', ''],
            ['', 'Q', '', '', '', '', '', ''],
            ['', '', 'q', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
        ]);

        $res = json_encode($board->getAllOnDiagonal(startPos: ['x' => 4, 'y' => 4], endPos: ['x' => 3, 'y' => 3]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":4,"y":4},{"x":3,"y":3}]');

        $res = json_encode($board->getAllOnDiagonal(startPos: ['x' => 3, 'y' => 6], endPos: ['x' => 1, 'y' => 4]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":2,"y":5},{"x":1,"y":4}]');

        $res = json_encode($board->getAllOnDiagonal(startPos: ['x' => 6, 'y' => 2], endPos: ['x' => 5, 'y' => 1]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":6,"y":2},{"x":5,"y":1}]');

        $res = json_encode($board->getAllOnDiagonal(startPos: ['x' => 7, 'y' => 3], endPos: ['x' => 6, 'y' => 2]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":7,"y":3},{"x":6,"y":2}]');
    }


    /* Get first */

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_first_on_diagonal_by_number(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', 'q', '', '', '', ''],
            ['', 'q', '', '', 'q', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', '', '', 'q', '', '', ''],
            ['', 'Q', '', '', '', '', '', ''],
            ['', '', 'q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $res = json_encode($board->getFirstOnDiagonal());
        $this->assertJsonStringEqualsJsonString($res, '{"x":1,"y":1}');

        $res = json_encode($board->getFirstOnDiagonal(3));
        $this->assertJsonStringEqualsJsonString($res, '{"x":0,"y":3}');

        $res = json_encode($board->getFirstOnDiagonal(-4));
        $this->assertJsonStringEqualsJsonString($res, '{"x":5,"y":1}');

        $res = json_encode($board->getFirstOnDiagonal(5));
        $this->assertJsonStringEqualsJsonString($res, '[]');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_first_on_diagonal_between(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', 'q', '', '', '', ''],
            ['', 'q', '', '', 'q', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', '', '', 'q', '', '', ''],
            ['', 'Q', '', '', '', '', '', ''],
            ['', '', 'q', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
        ]);

        $res = json_encode($board->getFirstOnDiagonal(startPos: ['x' => 3, 'y' => 3]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":3,"y":3}');

        $res = json_encode($board->getFirstOnDiagonal(startPos: ['x' => 1, 'y' => 4]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":1,"y":4}');

        $res = json_encode($board->getFirstOnDiagonal(startPos: ['x' => 5, 'y' => 1], endPos: ['x' => 6, 'y' => 2]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":5,"y":1}');

        $res = json_encode($board->getFirstOnDiagonal(startPos: ['x' => 6, 'y' => 2], endPos: ['x' => 7, 'y' => 3]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":6,"y":2}');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_first_on_diagonal_with_color(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', 'q', '', '', '', ''],
            ['', 'q', '', '', 'q', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', '', '', 'q', '', '', ''],
            ['', 'Q', '', '', '', '', '', ''],
            ['', '', 'q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $res = json_encode($board->getFirstOnDiagonal(color: 'white'));
        $this->assertJsonStringEqualsJsonString($res, '{"x":3,"y":3}');

        $res = json_encode($board->getFirstOnDiagonal(color: 'black'));
        $this->assertJsonStringEqualsJsonString($res, '{"x":1,"y":1}');

        $res = json_encode($board->getFirstOnDiagonal(3, color: 'white'));
        $this->assertJsonStringEqualsJsonString($res, '{"x":2,"y":5}');

        $res = json_encode($board->getFirstOnDiagonal(3, color: 'black'));
        $this->assertJsonStringEqualsJsonString($res, '{"x":0,"y":3}');

        $res = json_encode($board->getFirstOnDiagonal(-4, color: 'white'));
        $this->assertJsonStringEqualsJsonString($res, '{"x":5,"y":1}');

        $res = json_encode($board->getFirstOnDiagonal(-4, color: 'black'));
        $this->assertJsonStringEqualsJsonString($res, '{"x":6,"y":2}');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_first_on_diagonal_by_number_reverse(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', 'q', '', '', '', ''],
            ['', 'q', '', '', 'q', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', '', '', 'q', '', '', ''],
            ['', 'Q', '', '', '', '', '', ''],
            ['', '', 'q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        $res = json_encode($board->getFirstOnDiagonal(startPos: ['x' => 7,'y' => 7], endPos: ['x' => 0, 'y' => 0]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":4,"y":4}');

        $res = json_encode($board->getFirstOnDiagonal(startPos: ['x' => 4,'y' => 7], endPos: ['x' => 0, 'y' => 3]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":2,"y":5}');

        $res = json_encode($board->getFirstOnDiagonal(startPos: ['x' => 7,'y' => 3], endPos: ['x' => 4, 'y' => 0]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":6,"y":2}');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_first_on_diagonal_between_reverse(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', 'q', '', '', '', ''],
            ['', 'q', '', '', 'q', '', '', ''],
            ['', '', '', '', '', 'Q', '', ''],
            ['', '', '', 'Q', '', '', '', ''],
            ['', '', '', '', 'q', '', '', ''],
            ['', 'Q', '', '', '', '', '', ''],
            ['', '', 'q', '', '', '', '', ''],
            ['', '', '', 'q', '', '', '', ''],
        ]);

        $res = json_encode($board->getFirstOnDiagonal(startPos: ['x' => 4, 'y' => 4], endPos: ['x' => 3, 'y' => 3]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":4,"y":4}');

        $res = json_encode($board->getFirstOnDiagonal(startPos: ['x' => 3, 'y' => 6], endPos: ['x' => 1, 'y' => 4]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":2,"y":5}');

        $res = json_encode($board->getFirstOnDiagonal(startPos: ['x' => 6, 'y' => 2], endPos: ['x' => 5, 'y' => 1]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":6,"y":2}');

        $res = json_encode($board->getFirstOnDiagonal(startPos: ['x' => 7, 'y' => 3], endPos: ['x' => 6, 'y' => 2]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":7,"y":3}');
    }
}
