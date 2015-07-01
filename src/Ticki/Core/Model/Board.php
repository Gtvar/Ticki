<?php

namespace Ticki\Core\Model;

use Ticki\Core\Exception\ExceptionFactory;

/**
 * Game Board
 */
class Board
{
    const DEFAULT_SIDE_SIZE = 3;

	public static $availableSideCount = array(3, 5, 9);

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
	    $set = $this->morfSet();
	    $countSide = $this->getSideCount();

        for ($y = 1; $y <= $countSide; $y++) {
	        // horizontal
	        $count = $this->getCountByPositions($set, $this->getHorizontalPositions($y));
	        $this->findWinner($count);
	        if ($this->winnerType) {
		        return true;
	        }

	        // vertical
	        $count = $this->getCountByPositions($set, $this->getVerticalPositions($y));
	        $this->findWinner($count);
	        if ($this->winnerType) {
		        return true;
	        }
        }

	    // left bisector
	    $count = $this->getCountByPositions($set, $this->getLeftBisectorPositions());
	    $this->findWinner($count);
	    if ($this->winnerType) {
		    return true;
	    }

	    // right bisector
	    $count = $this->getCountByPositions($set, $this->getRightBisectorPositions());
	    $this->findWinner($count);
	    if ($this->winnerType) {
		    return true;
	    }

	    // All cells fill
	    if (!in_array(null, $this->set)) {
		    return true;
	    }

        return false;
    }

	/**
	 * @param $set
	 * @param $positions
	 *
	 * @return null|int
	 */
	protected function getCountByPositions($set, $positions)
	{
		$count = 0;
		foreach ($positions as $position) {
			if ($set[$position] === null) {
				return null;
			}

			$count += $set[$position];
		}

		return $count;
	}

	/**
	 * @param $count
	 */
	protected function findWinner($count)
	{
		if ($count === 0) {
			$this->winnerType = Cell::TIC;
		} elseif ($count == $this->getSideCount()) {
			$this->winnerType = Cell::TAC;
		}
	}

	/**
	 * Replace tic tac by zero or digit 1
	 *
	 * @return array
	 */
	protected function morfSet()
	{
		$set = array();
		foreach ($this->set as $pos => $value) {
			if ($value === null) {
				$set[$pos] = null;

				continue;
			}

			$set[$pos] = $value === Cell::TIC ? 0 : 1;
		}

		return $set;
	}

	/**
	 * Get horizontal positions by line
	 *
	 * @param $line
	 *
	 * @return array
	 */
	protected function getHorizontalPositions($line)
	{
		$countSide = $this->getSideCount();
		$array = array();
		for ($x = 1; $x <= $countSide; $x++) {
			$array[] = $countSide * ($line - 1) + $x;
		}

		return $array;
	}

	/**
	 * Get vertical positions by row
	 *
	 * @param $row
	 *
	 * @return array
	 */
	protected function getVerticalPositions($row)
	{
		$countSide = $this->getSideCount();
		$array = array();
		for ($x = 1; $x <= $countSide; $x++) {
			$array[] =  $countSide * ($x - 1) + $row;
		}

		return $array;
	}

	/**
	 * Get left Bisector positions
	 *
	 * @return array
	 */
	protected function getLeftBisectorPositions()
	{
		$countSide = $this->getSideCount();
		$array = array();
		for ($i = 1; $i <= $countSide; $i++) {
			$array[] = ($i - 1) * $countSide + $i;
		}

		return $array;
	}

	/**
	 * Get right bisector positions
	 *
	 * @return array
	 */
	protected function getRightBisectorPositions()
	{
		$countSide = $this->getSideCount();
		$array = array();
		for ($i = 1; $i <= $countSide; $i++) {
			$array[] =  $countSide * $i - ($i - 1);
		}

		return $array;
	}

	/**
	 * Get type of winner.
	 * If null not has winner
	 *
	 * @return null|string
	 */
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

	/**
	 * Check position for available
	 *
	 * @param $position
	 *
	 * @throws \Ticki\Core\Exception\RuntimeException
	 */
	public function checkPosition($position)
	{
		if(!in_array($position, $this->getFreeCell())) {
			throw ExceptionFactory::runtime("Wrong position");
		}
	}
}
