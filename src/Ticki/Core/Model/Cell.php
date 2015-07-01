<?php

namespace Ticki\Core\Model;

use Ticki\Core\Exception\ExceptionFactory;

/**
 * Immutable cell
 */
class Cell
{
    const TIC = 'o';
    const TAC = 'x';

    private $position;
    private $type;

    /**
     * Construct
     *
     * @param $position
     * @param $type
     */
    public function __construct($position, $type)
    {
        $this->position = $position;

        if (!in_array($type, array(self::TIC, self::TAC))) {
            throw ExceptionFactory::wrongCellException($type);
        }
        $this->type = $type;
    }

    /**
     * Get Position
     *
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Get Type
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get opposite to incoming type
     *
     * @param $type
     *
     * @return string
     */
    public static function getOppositeType($type)
    {
        return $type === self::TIC ? self::TAC: self::TIC;
    }

    /**
     * Fabric create tic cell
     *
     * @param $position
     *
     * @return static
     */
    public static function createTic($position)
    {
        return new static($position, self::TIC);
    }

    /**
     * Fabric create tac cell
     *
     * @param $position
     *
     * @return static
     */
    public static function createTac($position)
    {
        return new static($position, self::TAC);
    }
}
