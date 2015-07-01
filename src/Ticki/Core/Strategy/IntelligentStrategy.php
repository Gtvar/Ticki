<?php

namespace Ticki\Core\Strategy;

use Ticki\Core\Exception\ExceptionFactory;
use Ticki\Core\Model\Board;
use Ticki\Core\Model\Cell;

/**
 * Some more hard
 */
class IntelligentStrategy implements StrategyInterface
{
    /**
     * Just put first free cell on board
     */
    public function getCell(Board $board, $type)
    {
        $free = $board->getFreeCell();
	    if (empty($free)) {
		    ExceptionFactory::runtime("Not have free cells");
	    }

        $position = $free[rand(0, count($free) - 1)];

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
        return 'intelligent';
    }
}
