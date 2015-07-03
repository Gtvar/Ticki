<?php

namespace Ticki\Core\Tests;

use Ticki\Core\Model\Board;
use Ticki\Core\Model\Cell;

class BoardClass extends \PHPUnit_Framework_TestCase
{
	/**
	 * @param $p1
	 * @param $p2
	 * @param $p3
	 * @param $type
	 *
	 * @dataProvider horizontal
	 */
    public function testSuccessBoardFinishWin($p1, $p2, $p3, $type)
    {
        $board = new Board(3);
	    $this->assertTrue(!$board->isFinish());

	    $board->addCell((new Cell($p1, $type)));
	    $board->addCell((new Cell($p2, $type)));
	    $board->addCell((new Cell($p3, $type)));

		$this->assertTrue($board->isFinish());
        $this->assertEquals($type, $board->getWinnerType());
    }

	public function horizontal()
	{
		return array(
			// horizontal
			array(1, 2, 3, Cell::TAC),
			array(4, 5, 6, Cell::TIC),
			array(7, 8, 9, Cell::TAC),

			// bisector
			array(1, 5, 9, Cell::TAC),
			array(3, 5, 7, Cell::TAC),

			// vertical
			array(1, 4, 7, Cell::TAC),
			array(2, 5, 8, Cell::TAC),
			array(3, 6, 9, Cell::TAC),

		);
	}

	/**
	 * o   x   o
	 * x   o   x
	 * x   o   x
	 */
	public function testSuccessBoardFinishNoWinner()
	{
		$board = new Board(3);
		$type1 = Cell::TAC;
		$type2 = Cell::TIC;
		$this->assertTrue(!$board->isFinish());

		$board->addCell((new Cell(1, $type1)));
		$board->addCell((new Cell(2, $type2)));
		$board->addCell((new Cell(3, $type1)));
		$board->addCell((new Cell(4, $type2)));
		$board->addCell((new Cell(5, $type1)));
		$board->addCell((new Cell(6, $type2)));
		$board->addCell((new Cell(7, $type2)));
		$board->addCell((new Cell(8, $type1)));
		$board->addCell((new Cell(9, $type2)));

		$this->assertTrue($board->isFinish());
		$this->assertEquals(null, $board->getWinnerType());
	}

    /**
     *   o   o   3   4   5
         6   7   8   9  10
        11  12  13  14  15
        16   x   x   x  20
        21  22  23  24  25
     *
     */
    public function testNotFullLineByWin()
    {
        $type = Cell::TAC;
        $board = new Board(5, 3);
        $this->assertTrue(!$board->isFinish());

        $board->addCell((new Cell(17, $type)));
        $board->addCell((new Cell(18, $type)));
        $board->addCell((new Cell(19, $type)));

        $this->assertTrue($board->isFinish());
        $this->assertEquals($type, $board->getWinnerType());
    }
}
