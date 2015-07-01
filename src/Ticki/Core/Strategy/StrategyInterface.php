<?php

namespace Ticki\Core\Strategy;

use Ticki\Core\Model\Board;
use Ticki\Core\Model\Cell;

/**
 * Interface StrategyInterface
 * @package Ticki\Core\Strategy
 */
interface StrategyInterface
{
    /**
     * Add one cell on board using some strategy
     *
     * @param Board $board
     * @param $type
     *
     * @return Cell
     */
    public function getCell(Board $board, $type);

    /**
     * Get name
     *
     * @return string
     */
    public function name();
}
