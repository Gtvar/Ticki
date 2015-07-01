<?php

namespace Ticki\Core\Tests;

use Ticki\Core\Model\Board;

class BoardClass extends \PHPUnit_Framework_TestCase
{
    public function testSuccessCreateBoard()
    {
        $board = new Board(3);

        $this->assertEquals(3, $board->getSideCount());
    }

}
