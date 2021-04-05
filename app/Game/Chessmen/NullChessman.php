<?php

namespace App\Game\Chessmen;

use App\Game\MoveInfo;

class NullChessman extends AbstractChessman
{
    /**
     * @param array $to
     * @return MoveInfo
     */
    public function canMove(array $to): MoveInfo
    {
        return (new MoveInfo(
            status: false,
            message: 'This is empty cell',
        ));
    }
}
