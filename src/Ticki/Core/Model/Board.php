<?php

namespace Ticki\Core\Model;

use Ticki\Core\Exception\ExceptionFactory;

/**
 * Game Board
 */
class Board
{
    const DEFAULT_SIDE_SIZE = 3;

    /**
     * 2n + 1, n = 0, 1, 2, 4 ... 2^k
     *
     * @var array
     */
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
    private $kitCells = array();

    /**
     * How more need cell in one line for win.
     * Default equals to sideCount
     *
     * @var int
     */
    private $winCount;

    /**
     * @var null|string
     */
    private $winnerType = null;

    /**
     * Construct
     *
     * @param int $sideCount
     * @param int $winCount
     */
    public function __construct($sideCount, $winCount = 0)
    {
        $this->sideCount = $sideCount;
        $this->winCount = $winCount ?: $sideCount;

        $this->initialize();
    }

    /**
     * Create all cell
     */
    protected function initialize()
    {
        $count = $this->getCount();
        for ($i = 1; $i <= $count; $i++) {
            $this->kitCells[$i] = null;
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
     * Get KitCells
     *
     * @return array
     */
    public function getKitCells()
    {
        return $this->kitCells;
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
     * Get WinCount
     *
     * @return int
     */
    public function getWinCount()
    {
        return $this->winCount;
    }

    /**
     * Add cell only on free and exist cell
     *
     * @param Cell $cell
     */
    public function addCell(Cell $cell)
    {
        if (!isset($this->kitCells[$cell->getPosition()])) {
            ExceptionFactory::positionOutOfRangeException($cell->getPosition());
        }

        if (!empty($this->kitCells[$cell->getPosition()])) {
            ExceptionFactory::positionAlreadyExistException($cell->getPosition());
        }

        $this->kitCells[$cell->getPosition()] = $cell;
    }

    /**
     * Check for board is finish
     *
     * @return bool
     */
    public function isFinish()
    {
	    $countSide = $this->getSideCount();

        for ($y = 1; $y <= $countSide; $y++) {
	        // horizontal
            $winCount = $this->getWinCountByPositions($this->getHorizontalPositions($y));
	        $this->checkWinner($winCount);
	        if ($this->winnerType) {
		        return true;
	        }

	        // vertical
            $winCount = $this->getWinCountByPositions($this->getVerticalPositions($y));
	        $this->checkWinner($winCount);
	        if ($this->winnerType) {
		        return true;
	        }
        }

	    // left bisector
        $winCount = $this->getWinCountByPositions($this->getLeftBisectorPositions());
	    $this->checkWinner($winCount);
	    if ($this->winnerType) {
		    return true;
	    }

	    // right bisector
	    $winCount = $this->getWinCountByPositions($this->getRightBisectorPositions());
	    $this->checkWinner($winCount);
	    if ($this->winnerType) {
		    return true;
	    }

	    // All cells fill
	    if (!in_array(null, $this->kitCells)) {
		    return true;
	    }

        return false;
    }

	/**
	 * @param $positions
	 *
	 * @return WinCount
	 */
	protected function getWinCountByPositions($positions)
	{
		$set = $this->getKitCells();
        $winCount = new WinCount();
		foreach ($positions as $position) {
			if ($set[$position] === null) {
				continue;
			}

            $winCount->addCell($set[$position]);
		}

		return $winCount;
	}

    private function checkWinner(WinCount $winCount)
    {
        if ($winCount->getWinner() === null) {
            return false;
        }

        if ($winCount->getWinnerCount() == $this->winCount) {
            $this->winnerType = $winCount->getWinner();

            return true;
        }

        return false;
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
        foreach ($this->kitCells as $pos => $value) {
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
