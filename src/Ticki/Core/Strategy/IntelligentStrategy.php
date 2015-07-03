<?php

namespace Ticki\Core\Strategy;

use Ticki\Core\Exception\ExceptionFactory;
use Ticki\Core\Model\Board;
use Ticki\Core\Model\Cell;

/**
 * Some more hard
 */
class IntelligentStrategy extends AbstractStrategy
{
    /**
     * More variant
     *
     * @param Board $board
     * @param $type
     *
     * @return int
     */
    protected function getPosition(Board $board, $type)
    {
        $free = $board->getFreeCell();

        return $free[rand(0, count($free) - 1)];
    }

    /**
     * @inheritdoc
     */
    public function name()
    {
        return 'intelligent';
    }
}
