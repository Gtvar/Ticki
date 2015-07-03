<?php

namespace Ticki\Core\Strategy;

use Ticki\Core\Exception\ExceptionFactory;
use Ticki\Core\Model\Board;
use Ticki\Core\Model\Cell;

/**
 * Simple strategy
 */
class SimpleStrategy extends AbstractStrategy
{
    /**
     * Get new random position for free cells
     *
     * @param Board $board
     * @param $type
     *
     * @return int
     */
    protected function getPosition(Board $board, $type)
    {
        $free = $board->getFreeCell();

        return $free[0];
    }

    /**
     * @inheritdoc
     */
    public function name()
    {
        return 'simple';
    }
}
