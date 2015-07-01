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
	        $template = $pos <= 9 || $value !== null ? "  %s " : " %s ";
            $line .= sprintf($template, $value ?: $pos);

            if ($pos % $board->getSideCount() == 0) {
                $out .= $line . "\r\n";
                $line = '';
            }
        }

        return $out;
    }
}
