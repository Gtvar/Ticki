<?php

namespace Ticki\Core\Model;

use Ticki\Core\Exception\ExceptionFactory;

/**
 * Game Board
 */
class Board
{
    const DEFAULT_SIDE = 3;

    /**
     * Count one side
     *
     * @var int
     */
    private $sideCount;

    /**
     * 1  2  3
     *
     * 4  5  6
     *
     * 7  8  9
     *
     * @var array
     */
    private $set = array();

    /**
     * @var null|string
     */
    private $winnerType = null;

    /**
     * Construct
     *
     * @param Integer $sideCount
     */
    public function __construct($sideCount)
    {
        $this->sideCount = $sideCount;

        $this->initialize();
    }

    /**
     * Create all cell
     */
    protected function initialize()
    {
        $count = $this->getCount();
        for ($i = 1; $i <= $count; $i++) {
            $this->set[$i] = null;
        }
    }

    /**
     * Count all cell on board
     *
     * @return Int
     */
    public function getCount()
    {
        return $this->sideCount * $this->sideCount;
    }

    /**
     * Get Set
     *
     * @return array
     */
    public function getSet()
    {
        return $this->set;
    }

    /**
     * Get X
     *
     * @return mixed
     */
    public function getSideCount()
    {
        return $this->sideCount;
    }

    /**
     * Add cell only on free and exist cell
     *
     * @param Cell $cell
     */
    public function addCell(Cell $cell)
    {
        if (!isset($this->set[$cell->getPosition()])) {
            ExceptionFactory::positionOutOfRangeException($cell->getPosition());
        }

        if (!empty($this->set[$cell->getPosition()])) {
            ExceptionFactory::positionAlreadyExistException($cell->getPosition());
        }

        $this->set[$cell->getPosition()] = $cell->getType();
    }

    /**
     * Check for board is finish
     *
     * @return bool
     */
    public function isFinish()
    {
        // All cells fill
        if (!in_array(null, $this->set)) {
            return true;
        }


        for ($x = 1; $x <= $this->getSideCount() ; $x++) {
            for ($y = 1; $y <= $this->getSideCount() ; $y++) {


            }
        }

        return false;
    }

    public function getWinnerType()
    {
        return $this->winnerType;
    }

    /**
     * Get position of all cell
     *
     * @return array
     */
    public function getFreeCell()
    {
        $free = array();
        foreach ($this->set as $pos => $value) {
            if ($value === null) {
                $free[] = $pos;
            }
        }

        return $free;
    }
}
