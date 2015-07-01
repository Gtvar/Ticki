<?php

namespace Ticki\Core\Exception;

class ExceptionFactory
{
    public static function wrongCellException($type)
    {
        return new WrongCellException(sprintf("Invalid field type: %s", $type));
    }

    public static function positionOutOfRangeException($position)
    {
        return new PositionOutOfRangeException(sprintf("Position out of range: %s", $position));
    }

    public static function positionAlreadyExistException($position)
    {
        return new PositionOutOfRangeException(sprintf("Position already exist: %s", $position));
    }

    public static function undefinedStrategy($name)
    {
        return new UnknownStrategyException(sprintf("Unknown strategy: %s", $name));
    }
}