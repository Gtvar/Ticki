<?php

namespace Ticki\Core\Template;

use Ticki\Core\Model\Board;

/**
 * Class BoardTemplate
 */
class BoardTemplate
{
    /**
     * Simple draw board
     *
     * @param Board $board
     *
     * @return string
     */
    public static function draw(Board $board)
    {
        $out = $line = '';
        $set = $board->getSet();

        foreach ($set as $pos => $value) {
            $line .= sprintf(" %s ", $value ?: $pos);

            if ($pos % $board->getSideCount() == 0) {
                $out .= $line . "\r\n";
                $line = '';
            }
        }

        return $out;
    }
}
