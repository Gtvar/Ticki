<?php

namespace Ticki\Core\Strategy;

use Ticki\Core\Model\Board;
use Ticki\Core\Model\Cell;

/**
 * Simple random strategy
 */
class SimpleStrategy implements StrategyInterface
{
    /**
     * Just put first free cell on board
     */
    public function getCell(Board $board, $type)
    {
        $free = $board->getFreeCell();
        $position = $free[0];

        switch ($type) {
            case Cell::TIC:
                $cell = Cell::createTic($position);

                break;
            default :
                $cell = Cell::createTac($position);

        }

        return $cell;
    }

    /**
     * @inheritdoc
     */
    public function name()
    {
        return 'simple';
    }
}
