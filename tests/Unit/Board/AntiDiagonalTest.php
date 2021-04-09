<?php

namespace Tests\Unit\Board;

use App\Game\GameBoard;
use PHPUnit\Framework\TestCase;
use function Symfony\Component\String\s;

/*
            0 [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '],
            1 [' ', ' ', ' ', 'q', ' ', ' ', 'q', ' '],
            2 [' ', ' ', 'Q', ' ', ' ', ' ', ' ', ' '],
            3 [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '],
            4 ['q', ' ', ' ', ' ', ' ', ' ', ' ', 'Q'],
            5 [' ', ' ', 'q', ' ', ' ', ' ', ' ', ' '],
            6 [' ', 'Q', ' ', ' ', ' ', 'Q', ' ', ' '],
            7 [' ', ' ', ' ', ' ', 'q', ' ', ' ', ' '],
                0    1    2    3    4    5    6    7
*/
class AntiDiagonalTest extends TestCase
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
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', 'q', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['q', '', '', '', '', '', '', 'Q'],
            ['', '', 'q', '', '', '', '', ''],
            ['', 'Q', '', '', '', 'Q', '', ''],
            ['', '', '', '', 'q', '', '', ''],
        ]);

        $res = json_encode($board->getAllOnAntiDiagonal());
        $this->assertJsonStringEqualsJsonString($res, '[{"x":1,"y":6},{"x":5,"y":2},{"x":6,"y":1}]');

        $res = json_encode($board->getAllOnAntiDiagonal(3));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":1,"y":3},{"x":2,"y":2},{"x":4,"y":0}]');

        $res = json_encode($board->getAllOnAntiDiagonal(-4));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":4,"y":7},{"x":6,"y":5},{"x":7,"y":4}]');

        $res = json_encode($board->getAllOnAntiDiagonal(5));
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
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', 'q', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['q', '', '', '', '', '', '', 'Q'],
            ['', '', 'q', '', '', '', '', ''],
            ['', 'Q', '', '', '', 'Q', '', ''],
            ['', '', '', '', 'q', '', '', ''],
        ]);

        $res = json_encode($board->getAllOnAntiDiagonal(startPos: ['x' => 4, 'y' => 3]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":5,"y":2},{"x":6,"y":1}]');

        $res = json_encode($board->getAllOnAntiDiagonal(3, startPos: ['x' => 1, 'y' => 3], endPos: ['x' => 3, 'y' => 1]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":1,"y":3},{"x":2,"y":2}]');

        $res = json_encode($board->getAllOnAntiDiagonal(-4, startPos: ['x' => 7, 'y' => 4]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":7,"y":4}]');

        $res = json_encode($board->getAllOnAntiDiagonal(-4, endPos: ['x' => 5, 'y' => 6]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":4,"y":7}]');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_all_on_diagonal_with_color(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', 'q', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['q', '', '', '', '', '', '', 'Q'],
            ['', '', 'q', '', '', '', '', ''],
            ['', 'Q', '', '', '', 'Q', '', ''],
            ['', '', '', '', 'q', '', '', ''],
        ]);

        $res = json_encode($board->getAllOnAntiDiagonal(color: 'white'));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":6,"y":1}]');

        $res = json_encode($board->getAllOnAntiDiagonal(color: 'black'));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":1,"y":6},{"x":5,"y":2}]');

        $res = json_encode($board->getAllOnAntiDiagonal(3, color: 'white'));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":2,"y":2}]');

        $res = json_encode($board->getAllOnAntiDiagonal(3, color: 'black'));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":1,"y":3},{"x":4,"y":0}]');

        $res = json_encode($board->getAllOnAntiDiagonal(-4, color: 'white'));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":4,"y":7},{"x":6,"y":5}]');

        $res = json_encode($board->getAllOnAntiDiagonal(-4, color: 'black'));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":7,"y":4}]');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_all_on_diagonal_by_number_reverse(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', 'q', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['q', '', '', '', '', '', '', 'Q'],
            ['', '', 'q', '', '', '', '', ''],
            ['', 'Q', '', '', '', 'Q', '', ''],
            ['', '', '', '', 'q', '', '', ''],
        ]);

        $res = json_encode($board->getAllOnAntiDiagonal(startPos: ['x' => 7, 'y' => 0], endPos: ['x' => 0, 'y' => 7]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":6,"y":1},{"x":5,"y":2},{"x":1,"y":6}]');

        $res = json_encode($board->getAllOnAntiDiagonal(startPos: ['x' => 4, 'y' => 0], endPos: ['x' => 0, 'y' => 4]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":4,"y":0},{"x":2,"y":2},{"x":1,"y":3}]');

        $res = json_encode($board->getAllOnAntiDiagonal(startPos: ['x' => 7, 'y' => 4], endPos: ['x' => 4, 'y' => 7]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":7,"y":4},{"x":6,"y":5},{"x":4,"y":7}]');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_all_on_diagonal_between_reverse(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', 'q', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['q', '', '', '', '', '', '', 'Q'],
            ['', '', 'q', '', '', '', '', ''],
            ['', 'Q', '', '', '', 'Q', '', ''],
            ['', '', '', '', 'q', '', '', ''],
        ]);

        $res = json_encode($board->getAllOnAntiDiagonal(startPos: ['x' => 7, 'y' => 0], endPos: ['x' => 4, 'y' => 3]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":6,"y":1},{"x":5,"y":2}]');

        $res = json_encode($board->getAllOnAntiDiagonal(startPos: ['x' => 3, 'y' => 1], endPos: ['x' => 1, 'y' => 3]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":2,"y":2},{"x":1,"y":3}]');

        $res = json_encode($board->getAllOnAntiDiagonal(startPos: ['x' => 6, 'y' => 5], endPos: ['x' => 4, 'y' => 7]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":6,"y":5},{"x":4,"y":7}]');

        $res = json_encode($board->getAllOnAntiDiagonal(startPos: ['x' => 4, 'y' => 7], endPos: ['x' => 4, 'y' => 7]));
        $this->assertJsonStringEqualsJsonString($res, '[{"x":4,"y":7}]');
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
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', 'q', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['q', '', '', '', '', '', '', 'Q'],
            ['', '', 'q', '', '', '', '', ''],
            ['', 'Q', '', '', '', 'Q', '', ''],
            ['', '', '', '', 'q', '', '', ''],
        ]);

        $res = json_encode($board->getFirstOnAntiDiagonal());
        $this->assertJsonStringEqualsJsonString($res, '{"x":1,"y":6}');

        $res = json_encode($board->getFirstOnAntiDiagonal(3));
        $this->assertJsonStringEqualsJsonString($res, '{"x":1,"y":3}');

        $res = json_encode($board->getFirstOnAntiDiagonal(-4));
        $this->assertJsonStringEqualsJsonString($res, '{"x":4,"y":7}');

        $res = json_encode($board->getFirstOnAntiDiagonal(5));
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
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', 'q', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['q', '', '', '', '', '', '', 'Q'],
            ['', '', 'q', '', '', '', '', ''],
            ['', 'Q', '', '', '', 'Q', '', ''],
            ['', '', '', '', 'q', '', '', ''],
        ]);

        $res = json_encode($board->getFirstOnAntiDiagonal(startPos: ['x' => 4, 'y' => 3]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":5,"y":2}');

        $res = json_encode($board->getFirstOnAntiDiagonal(3, startPos: ['x' => 1, 'y' => 3], endPos: ['x' => 3, 'y' => 1]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":1,"y":3}');

        $res = json_encode($board->getFirstOnAntiDiagonal(-4, startPos: ['x' => 7, 'y' => 4]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":7,"y":4}');

        $res = json_encode($board->getFirstOnAntiDiagonal(-4, endPos: ['x' => 5, 'y' => 6]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":4,"y":7}');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_first_on_diagonal_with_color(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', 'q', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['q', '', '', '', '', '', '', 'Q'],
            ['', '', 'q', '', '', '', '', ''],
            ['', 'Q', '', '', '', 'Q', '', ''],
            ['', '', '', '', 'q', '', '', ''],
        ]);

        $res = json_encode($board->getFirstOnAntiDiagonal(color: 'white'));
        $this->assertJsonStringEqualsJsonString($res, '{"x":6,"y":1}');

        $res = json_encode($board->getFirstOnAntiDiagonal(color: 'black'));
        $this->assertJsonStringEqualsJsonString($res, '{"x":1,"y":6}');

        $res = json_encode($board->getFirstOnAntiDiagonal(3, color: 'white'));
        $this->assertJsonStringEqualsJsonString($res, '{"x":2,"y":2}');

        $res = json_encode($board->getFirstOnAntiDiagonal(3, color: 'black'));
        $this->assertJsonStringEqualsJsonString($res, '{"x":1,"y":3}');

        $res = json_encode($board->getFirstOnAntiDiagonal(-4, color: 'white'));
        $this->assertJsonStringEqualsJsonString($res, '{"x":4,"y":7}');

        $res = json_encode($board->getFirstOnAntiDiagonal(-4, color: 'black'));
        $this->assertJsonStringEqualsJsonString($res, '{"x":7,"y":4}');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_first_on_diagonal_by_number_reverse(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', 'q', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['q', '', '', '', '', '', '', 'Q'],
            ['', '', 'q', '', '', '', '', ''],
            ['', 'Q', '', '', '', 'Q', '', ''],
            ['', '', '', '', 'q', '', '', ''],
        ]);

        $res = json_encode($board->getFirstOnAntiDiagonal(startPos: ['x' => 7, 'y' => 0], endPos: ['x' => 0, 'y' => 7]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":6,"y":1}');

        $res = json_encode($board->getFirstOnAntiDiagonal(startPos: ['x' => 4, 'y' => 0], endPos: ['x' => 0, 'y' => 4]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":4,"y":0}');

        $res = json_encode($board->getFirstOnAntiDiagonal(startPos: ['x' => 7, 'y' => 4], endPos: ['x' => 4, 'y' => 7]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":7,"y":4}');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_first_on_diagonal_between_reverse(): void
    {
        $board = GameBoard::createByBoard([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', 'q', '', '', 'q', ''],
            ['', '', 'Q', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['q', '', '', '', '', '', '', 'Q'],
            ['', '', 'q', '', '', '', '', ''],
            ['', 'Q', '', '', '', 'Q', '', ''],
            ['', '', '', '', 'q', '', '', ''],
        ]);

        $res = json_encode($board->getFirstOnAntiDiagonal(startPos: ['x' => 7, 'y' => 0], endPos: ['x' => 4, 'y' => 3]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":6,"y":1}');

        $res = json_encode($board->getFirstOnAntiDiagonal(startPos: ['x' => 3, 'y' => 1], endPos: ['x' => 1, 'y' => 3]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":2,"y":2}');

        $res = json_encode($board->getFirstOnAntiDiagonal(startPos: ['x' => 6, 'y' => 5], endPos: ['x' => 4, 'y' => 7]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":6,"y":5}');

        $res = json_encode($board->getFirstOnAntiDiagonal(startPos: ['x' => 4, 'y' => 7], endPos: ['x' => 4, 'y' => 7]));
        $this->assertJsonStringEqualsJsonString($res, '{"x":4,"y":7}');
    }
}
