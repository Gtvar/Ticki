<?php

namespace Ticki\Core\Model;

use Ticki\Core\Exception\ExceptionFactory;

/**
 * Determine winner
 */
class WinCount
{
    /**
     * @var array
     */
    protected $count = array();

    /**
     * Construct
     */
    public function __construct()
    {
        $this->count = array(
            Cell::TIC => 0,
            Cell::TAC => 0
        );
    }

    /**
     * @param Cell $cell
     */
    public function addCell(Cell $cell)
    {
        $this->count[$cell->getType()]++;
    }

    /**
     * Get Winner
     *
     * @return null|string
     */
    public function getWinner()
    {
        if ($this->count[Cell::TIC] == $this->count[Cell::TAC]) {
            return null;
        }

        return $this->count[Cell::TIC] > $this->count[Cell::TAC] ? Cell::TIC : Cell::TAC;
    }

    /**
     * Get count of win
     *
     * @return null
     */
    public function getWinnerCount()
    {
        if ($this->getWinner() === null) {
            return null;
        }

        return $this->getByType($this->getWinner());
    }

    /**
     * @param $type
     *
     * @return mixed
     * @throws \Ticki\Core\Exception\RuntimeException
     */
    public function getByType($type)
    {
        if (!in_array($type, array(Cell::TIC, Cell::TAC))) {
            throw ExceptionFactory::runtime(sprintf("Invalid type: %s", $type));
        }

        return $this->count[$type];
    }
}
