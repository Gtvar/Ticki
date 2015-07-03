<?php

namespace Ticki\Core\Strategy;

use Ticki\Core\Exception\ExceptionFactory;
use Ticki\Core\Model\Board;
use Ticki\Core\Model\Cell;

/**
 * Base class for strategy
 */
abstract class AbstractStrategy implements StrategyInterface
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

        $position = $this->getPosition($board, $type);

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
     * Get new position
     *
     * @param Board $board
     * @param $type
     *
     * @return int
     */
    abstract protected function getPosition(Board $board, $type);

    /**
     * @inheritdoc
     */
    public function name()
    {
        return 'intelligent';
    }
}
