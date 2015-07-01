<?php

namespace Ticki\Core;

use Ticki\Core\Model\Board;
use Ticki\Core\Model\Cell;
use Ticki\Core\Strategy\StrategyManager;
use Ticki\Core\Exception\ExceptionFactory;

/**
 * Main game class
 */
class TicTac
{
    const STAGE_GAME = 0;
    const STAGE_YOU_WIN = 1;
    const STAGE_YOU_LOST = 2;
    const STAGE_DEAD_HEAT = 3;

    /**
     * @var Strategy\StrategyManager
     */
    protected $strategyManager;

    /**
     *
     * @var Strategy\StrategyInterface
     */
    protected $strategy;

    /**
     * @var Model\Board
     */
    protected $board;

    /**
     * @var string
     */
    private $myType;

    /**
     * @var string
     */
    private $oppositeType;

    /**
     * Construct
     *
     * @param $strategy
     * @param $sideCount
     * @param $myType
     */
    public function __construct($strategy, $sideCount, $myType)
    {
	    if (!in_array($myType, array(Cell::TIC, Cell::TAC))) {
		    throw ExceptionFactory::wrongCellException($myType);
	    }

	    if (!in_array($sideCount, Board::$availableSideCount)) {
		    throw ExceptionFactory::runtime("Wrong side number");
	    }

        $this->strategy = $this->getStrategyManager()->getByName($strategy);
        $this->board = new Board($sideCount);
        $this->myType = $myType;
        $this->oppositeType = Cell::getOppositeType($myType);
    }

    /**
     * Main game loop
     *
     * @param $position
     *
     * @return int
     */
    public function game($position)
    {
	    $this->board->checkPosition($position);
        $this->step($this->myType, $position);

        if (!$this->board->isFinish()) {
            return self::STAGE_GAME;
        }

        if ($this->board->getWinnerType() === $this->myType) {
            return self::STAGE_YOU_WIN;
        } elseif ($this->board->getWinnerType() === $this->oppositeType) {
            return self::STAGE_YOU_LOST;
        }

        return self::STAGE_DEAD_HEAT;
    }

    /**
     * One step
     *
     * @param $type
     * @param $position
     *
     * @throws Exception\WrongCellException
     */
    public function step($type, $position)
    {
        switch ($type) {
            case Cell::TIC :
                $cell = Cell::createTic($position);
                break;

            case Cell::TAC :
                $cell = Cell::createTac($position);
                break;

            default :
                throw ExceptionFactory::wrongCellException($type);
                break;
        }

        // Human step
        $this->board->addCell($cell);

        // Strategy step
        $cell = $this->strategy->getCell($this->board, $this->oppositeType);
        $this->board->addCell($cell);
    }

    /**
     * Get Board
     *
     * @return Board
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Get strategy Manager
     *
     * @return StrategyManager
     */
    public function getStrategyManager()
    {
        if (!$this->strategyManager) {
            $this->strategyManager = new StrategyManager();
        }

        return $this->strategyManager;
    }
}
