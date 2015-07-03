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
     * @todo: check if no have winner before all cell fill
     *
     * @return bool
     */
    public function isFinish()
    {
	    $countSide = $this->getSideCount();

        for ($y = 1; $y <= $countSide; $y++) {
            $this->processPositions('Horizontal', $y);
	        if ($this->winnerType) {
		        return true;
	        }

            $this->processPositions('Vertical', $y);
	        if ($this->winnerType) {
		        return true;
	        }
        }

        $this->processPositions('LeftBisector');
	    if ($this->winnerType) {
		    return true;
	    }

        $this->processPositions('RightBisector');
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
     * Process one line
     *
     * @param $category
     * @param null $line
     *
     */
    protected function processPositions($category, $line = null)
    {
        $method = 'get' . $category . 'Positions';
        $positions = $this->{$method}($line);
        $winCount = $this->getWinCountByPositions($positions);
        $this->checkWinner($winCount, $positions);
    }

	/**
	 * @param $positions
	 *
	 * @return WinCount
	 */
	public function getWinCountByPositions($positions)
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

    /**
     * Check for we have winner
     *
     * @param WinCount $winCount
     * @param $positions
     *
     * @return bool
     */
    private function checkWinner(WinCount $winCount, $positions)
    {
        if ($winCount->getWinner() === null) {
            return false;
        }

        if ($winCount->getWinnerCount() == $this->winCount) {
            $winner = $winCount->getWinner();

            // Check sequence for "at a stretch"
            $count = 0;
            foreach ($positions as $value) {
                /** @var \Ticki\Core\Model\Cell $current */
                $current = $this->kitCells[$value];
                if ($current === null) {
                    $count = 0;

                    continue;
                }

                if ($current->getType() === $winner) {
                    $count++;
                } else {
                    $count = 0;
                }

                if ($this->winCount == $count) {
                    $this->winnerType = $winner;

                    return true;
                }
            }

            return false;
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
	public function getHorizontalPositions($line)
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
    public function getVerticalPositions($row)
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
    public function getLeftBisectorPositions()
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
    public function getRightBisectorPositions()
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
