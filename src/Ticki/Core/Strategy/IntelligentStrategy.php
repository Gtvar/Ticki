<?php

namespace Ticki\Core\Strategy;

use Ticki\Core\Model\Board;
use Ticki\Core\Model\Cell;
use Ticki\Core\Model\WinCount;

/**
 * Some more hard
 */
class IntelligentStrategy extends AbstractStrategy
{
    protected $winCounts = array(
        Cell::TIC => 0,
        Cell::TAC => 0
    );

    protected $winLines = array(
        Cell::TIC => array(),
        Cell::TAC => array()
    );

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
        $countSide = $board->getSideCount();
        for ($y = 1; $y <= $countSide; $y++) {
            $this->processPositions($board, 'Horizontal', $y);
            $this->processPositions($board, 'Vertical', $y);
        }
        $this->processPositions($board, 'LeftBisector');
        $this->processPositions($board, 'RightBisector');

        $line = $this->getWinLine($type);
        $free = $board->getFreeCell();

        foreach ($line as $value) {
            if (in_array($value, $free)) {
                return $value;
            }
        }

        // no find best position
        return $free[rand(0, count($free) - 1)];
    }

    /**
     * Find winner and store win lines
     *
     * @param Board $board
     * @param $category
     * @param null $line
     *
     */
    protected function processPositions(Board $board, $category, $line = null)
    {
        $method = 'get' . $category . 'Positions';
        $positions = $board->{$method}($line);
        $winCount = $board->getWinCountByPositions($positions);
        if ($this->mergeWinCount($winCount)) {
            $this->winLines[$winCount->getWinner()] = $positions;
        }
    }

    /**
     * Increase winner count in line
     *
     * @param WinCount $winCount
     *
     * @return bool
     */
    protected function mergeWinCount(WinCount $winCount)
    {
        if ($winCount->getWinner() === null) {
            return false;
        }

        if ($this->winCounts[$winCount->getWinner()] > $winCount->getWinnerCount()) {
            return false;
        }

        $this->winCounts[$winCount->getWinner()] = $winCount->getWinnerCount();

        return true;
    }

    /**
     * Get best line
     *
     * @param $type
     */
    protected function getWinLine($type)
    {
        $oppositeType = Cell::getOppositeType($type);
        if ($this->winCounts[$type] < $this->winCounts[$oppositeType]) {
            return $this->winLines[$oppositeType];
        } else {
            return $this->winLines[$type];
        }
    }

    /**
     * @inheritdoc
     */
    public function name()
    {
        return 'intelligent';
    }
}
