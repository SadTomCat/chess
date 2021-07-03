<?php

namespace App\Game;

use App\Game\Chessmen\AbstractChessman;
use App\Models\Game;

class MoveValidation
{
    public const NOT_YOU_MOVE = 'Not you move';

    public const IN_CHECK = 'Your king in check';

    public const INCORRECT_RANGE = 'You try to move out of the board range';

    private bool $rangeValidationStatus = true;

    private AbstractChessman $fromChessman;

    private GameBoard $board;

    private string $color;

    private int $countMove;

    public function __construct(
        private int $userId,
        private Game $game,
        private array $from,
        private array $to
    )
    {
        $this->validateRanges($this->from, $this->to);

        if ($this->rangeValidationStatus === false) {
            return;
        }

        $this->board = GameBoard::createByGame($game);
        $this->fromChessman = $this->board->getChessman($from);

        $this->color = $this->game->users()
                                  ->where('user_id', $this->userId)
                                  ->withPivot('color')
                                  ->first()->pivot->color;

        $this->countMove = $this->game->moves()->count();
    }

    /**
     * It checks move on rules, safe for king, checkmate for players.
     *
     * @return MoveInfo
     * @throws \Exception
     */
    public function validate(): MoveInfo
    {
        if ($this->rangeValidationStatus === false) {
            return (new MoveInfo(status: false, message: self::INCORRECT_RANGE));
        }

        // checks if it is the player's turn to make a move
        if (($this->countMove % 2 === 0 && $this->color === 'black') || ($this->countMove % 2 !== 0 && $this->color === 'white')
            || $this->color !== $this->fromChessman->getColor()) {
            return (new MoveInfo(status: false, message: self::NOT_YOU_MOVE));
        }

        $moveInfo = $this->fromChessman->moveValidation($this->to);

        if ($moveInfo->getStatus() === false) {
            return $moveInfo;
        }

        return $this->validAfterMove($moveInfo);
    }

    /**
     * @param array $from
     * @param array $to
     */
    private function validateRanges(array $from, array $to): void
    {
        if ($from['x'] > 7 || $from['x'] < 0 || $from['y'] > 7 || $from['y'] < 0 || $to['x'] > 7 || $to['x'] < 0
            || $to['y'] > 7 || $to['y'] < 0) {
            $this->rangeValidationStatus = false;
        }
    }

    /**
     * checks if the opponent has at least one move and that current player is not on check.
     * If this method returns false then the current player makes a checkmate.
     *
     * @param MoveInfo $moveInfo
     * @return MoveInfo
     * @throws \Exception
     */
    private function validAfterMove(MoveInfo $moveInfo): MoveInfo
    {
        $boardAfterMove = $this->board->createAfterMove($this->from, $this->to);
        $kingAfterMove = $boardAfterMove->getKing($this->fromChessman->getColor());

        if (empty($kingAfterMove->inSafety()) === false) {
            return (new MoveInfo(status: false, message: self::IN_CHECK));
        }

        $oppColor = $this->color === 'white' ? 'black' : 'white';
        $opponentMoveExist = (new MateValidation($boardAfterMove, $oppColor))->validate();

        return $opponentMoveExist === true
            ? $moveInfo
            : (new MoveInfo('mate', $this->from, $this->to, true));
    }
}
